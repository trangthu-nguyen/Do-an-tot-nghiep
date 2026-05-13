@extends('customer.layout')

@section('title','Địa chỉ đã lưu')

@section('content')

<style>
    :root{
        --primary:#7b5554;
        --primary-dark:#684847;
        --outline:#eadede;
        --text:#2f2323;
        --muted:#7d7272;
    }

    .address-wrapper{
        max-width: 520px;
        margin: 0 auto;
        background: white;
        border-radius: 28px;
        border: 1px solid var(--outline);
        box-shadow: 0 15px 45px rgba(123,85,84,0.12);
        padding: 30px;
    }

    .back-btn{
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: white;
        border: 1px solid var(--outline);
        display:flex;
        align-items:center;
        justify-content:center;
        text-decoration:none;
        color: var(--primary);
        box-shadow: 0 10px 25px rgba(123,85,84,0.12);
        margin-bottom: 16px;
    }

    .title{
        text-align:center;
        font-family:'Noto Serif', serif;
        font-weight: 800;
        font-size: 26px;
        margin-bottom: 18px;
        color: var(--text);
    }

    label{
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 6px;
    }

    textarea{
        border-radius: 18px !important;
        border: 1px solid var(--outline) !important;
        padding: 14px 16px;
        font-weight: 700;
        resize: none;
        min-height: 120px;
    }

    textarea:focus{
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 4px rgba(123,85,84,0.15) !important;
    }

    .btn-save{
        background: var(--primary);
        border: none;
        color: white;
        border-radius: 18px;
        padding: 14px;
        font-weight: 900;
        width: 100%;
        margin-top: 20px;
        transition:0.2s;
    }

    .btn-save:hover{
        background: var(--primary-dark);
    }
</style>

<div class="address-wrapper">

    <a href="{{ route('customer.profile.index') }}" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </a>

    <div class="title">Địa chỉ đã lưu</div>

    <form action="{{ route('customer.profile.address.update') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Địa chỉ</label>
            <textarea name="address" class="form-control"
                      placeholder="Nhập địa chỉ của bạn...">{{ old('address', $customer->address) }}</textarea>
        </div>

        <button class="btn-save">
            Lưu địa chỉ <i class="bi bi-check2-circle"></i>
        </button>

    </form>

</div>

@endsection