<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Service;
use Carbon\Carbon;
use Exception;

class BookingService
{
    // ============================
    // 1 SERVICE = 1 BOOKING
    // ============================
    public function createBookingSingleService(array $data, $customer_id)
    {
        $service = Service::where('service_id', $data['service_id'])->firstOrFail();

        // Kiểm tra ngày giờ hợp lệ
        $bookingDate = $data['booking_date'];
        $bookingTime = $data['booking_time'];

        // Nếu slot này đã bị đặt -> báo lỗi
        $exists = Booking::where('booking_date', $bookingDate)
            ->where('booking_time', $bookingTime)
            ->where('status', '!=', 4) // 4 = đã hủy
            ->exists();

        if ($exists) {
            throw new Exception("Khung giờ này đã có người đặt. Vui lòng chọn giờ khác!");
        }

        // Tạo booking
        $booking = Booking::create([
            'customer_id'   => $customer_id,
            'booking_date'  => $bookingDate,
            'booking_time'  => $bookingTime,
            'address'       => $data['address'] ?? '',
            'note'          => $data['note'] ?? null,
            'total_amount'  => $service->price,
            'status'        => 0, // chờ xác nhận
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // Tạo booking detail
        BookingDetail::create([
            'booking_id' => $booking->booking_id,
            'service_id' => $service->service_id,
            'price'      => $service->price,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $booking;
    }


    // ============================
    // API SLOT TRỐNG
    // ============================
    public function getAvailableSlots($date, $service_id)
    {
        // Các slot cố định (rạp phim style)
        $timeSlots = [
            "08:00","09:00","10:00","11:00",
            "13:00","14:00","15:00","16:00",
            "18:00","19:00","20:00","21:00"
        ];

        $result = [];

        foreach ($timeSlots as $slot) {

            $exists = Booking::where('booking_date', $date)
                ->where('booking_time', $slot)
                ->where('status', '!=', 4) // 4 = đã hủy
                ->exists();

            $result[] = [
                "time" => $slot,
                "available" => !$exists
            ];
        }

        return $result;
    }


    // ============================
    // HỦY BOOKING
    // ============================
    public function cancelBookingByCustomer($booking, $customer_id)
    {
        if ($booking->customer_id != $customer_id) {
            throw new Exception("Bạn không có quyền hủy booking này!");
        }

        if ($booking->status != 0) {
            throw new Exception("Chỉ booking đang chờ xác nhận mới được hủy!");
        }

        $booking->status = 4; // đã hủy
        $booking->save();

        return true;
    }
    // ============================
// NHÂN VIÊN NHẬN BOOKING
// ============================
public function acceptBookingByStaff($booking, $staff_id)
{
    if ($booking->staff_id != null) {
        throw new Exception("Lịch này đã có nhân viên nhận rồi!");
    }

    if ($booking->status != 0) {
        throw new Exception("Chỉ lịch đang chờ xác nhận mới được nhận!");
    }

    $booking->staff_id = $staff_id;
    $booking->status = 1; // đã xác nhận
    $booking->save();

    return true;
}
}