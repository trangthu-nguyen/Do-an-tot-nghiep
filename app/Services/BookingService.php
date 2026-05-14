<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Staff;
use Exception;
use Illuminate\Support\Facades\DB;

class BookingService
{
    const STATUS_PENDING = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_DOING = 2;
    const STATUS_DONE = 3;
    const STATUS_CANCELLED = 4;

    public function createBookingSingleService(array $data, $customerId): Booking
    {
        return DB::transaction(function () use ($data, $customerId) {
            $service = Service::where('service_id', $data['service_id'])->firstOrFail();

            $this->checkSlotAvailable($data['booking_date'], $data['booking_time']);

            $booking = Booking::create([
                'customer_id' => $customerId,
                'booking_date' => $data['booking_date'],
                'booking_time' => $data['booking_time'],
                'address' => $data['address'] ?? '',
                'note' => $data['note'] ?? null,
                'total_amount' => $service->price,
                'status' => self::STATUS_PENDING,
            ]);

            BookingDetail::create([
                'booking_id' => $booking->booking_id,
                'service_id' => $service->service_id,
                'quantity' => 1,
                'price' => $service->price,
            ]);

            $this->createPayment($booking, $service->price, $data['payment_method'] ?? 'cod');

            $this->sendNewBookingNotifications($booking, $service, $customerId);

            return $booking;
        });
    }

    public function getAvailableSlots($date, $serviceId): array
    {
        $timeSlots = [
            '08:00', '09:00', '10:00', '11:00',
            '13:00', '14:00', '15:00', '16:00',
            '18:00', '19:00', '20:00', '21:00',
        ];

        $result = [];

        foreach ($timeSlots as $slot) {
            $exists = Booking::where('booking_date', $date)
                ->where('booking_time', $slot)
                ->where('status', '!=', self::STATUS_CANCELLED)
                ->exists();

            $result[] = [
                'time' => $slot,
                'available' => !$exists,
            ];
        }

        return $result;
    }

    public function cancelBookingByCustomer($booking, $customerId): bool
    {
        if ($booking->customer_id != $customerId) {
            throw new Exception('Bạn không có quyền hủy booking này!');
        }

        if ($booking->status != self::STATUS_PENDING) {
            throw new Exception('Chỉ booking đang chờ xác nhận mới được hủy!');
        }

        $booking->status = self::STATUS_CANCELLED;
        $booking->save();

        $this->sendCancelBookingNotifications($booking, $customerId);

        return true;
    }

    public function acceptBookingByStaff($booking, $staffId): bool
    {
        if ($booking->staff_id != null) {
            throw new Exception('Lịch này đã có nhân viên nhận rồi!');
        }

        if ($booking->status != self::STATUS_PENDING) {
            throw new Exception('Chỉ lịch đang chờ xác nhận mới được nhận!');
        }

        $booking->staff_id = $staffId;
        $booking->status = self::STATUS_CONFIRMED;
        $booking->save();

        return true;
    }

    private function checkSlotAvailable($bookingDate, $bookingTime): void
    {
        $exists = Booking::where('booking_date', $bookingDate)
            ->where('booking_time', $bookingTime)
            ->where('status', '!=', self::STATUS_CANCELLED)
            ->exists();

        if ($exists) {
            throw new Exception('Khung giờ này đã có người đặt. Vui lòng chọn giờ khác!');
        }
    }

    private function createPayment(Booking $booking, $amount, string $paymentMethod): void
    {
        $isOnline = in_array($paymentMethod, ['momo', 'vnpay', 'bank']);

        Payment::create([
            'booking_id' => $booking->booking_id,
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'payment_status' => $isOnline ? 'paid' : 'pending',
            'payment_date' => $isOnline ? now() : null,
            'transaction_id' => $isOnline ? strtoupper($paymentMethod) . '-' . time() : null,
        ]);
    }

    private function sendNewBookingNotifications(Booking $booking, Service $service, $customerId): void
    {
        foreach (Admin::all() as $admin) {
            $this->createNotification(
                'admin',
                $admin->admin_id,
                'Có lịch đặt mới',
                'Khách hàng vừa đặt lịch #' . $booking->booking_id
            );
        }

        foreach (Staff::all() as $staff) {
            $this->createNotification(
                'staff',
                $staff->staff_id,
                'Có lịch đặt mới đang chờ nhận',
                'Khách hàng vừa đặt Booking #' . $booking->booking_id .
                ' - Dịch vụ: ' . $service->service_name .
                '. Bạn có thể vào Danh sách lịch đặt để nhận lịch.'
            );
        }

        $this->createNotification(
            'customer',
            $customerId,
            'Đặt lịch thành công',
            'Bạn đã đặt lịch #' . $booking->booking_id . ' thành công. Vui lòng chờ admin xác nhận.'
        );
    }

    private function sendCancelBookingNotifications(Booking $booking, $customerId): void
    {
        $this->createNotification(
            'customer',
            $customerId,
            'Hủy lịch thành công',
            'Bạn đã hủy lịch #' . $booking->booking_id . ' thành công.'
        );

        if ($booking->staff_id) {
            $this->createNotification(
                'staff',
                $booking->staff_id,
                'Khách hàng đã hủy lịch',
                'Booking #' . $booking->booking_id . ' đã được khách hàng hủy.'
            );
        }
    }

    private function createNotification($userType, $userId, $title, $content): void
    {
        Notification::create([
            'user_type' => $userType,
            'user_id' => $userId,
            'title' => $title,
            'content' => $content,
            'is_read' => 0,
            'created_at' => now(),
        ]);
    }
}