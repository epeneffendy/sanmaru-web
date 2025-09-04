<style>
    .container {
        width: 80%;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-family: sans-serif;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
    }

    .invoice-details, .payment-details {
        margin-bottom: 20px;
    }

    .signature {
        text-align: center;
        margin-top: 20px;
    }
</style>

<div class="container">
    <h1>Pembayaran Terkonfirmasi</h1>
    <hr>

    <div class="invoice-details">
        <p><strong>Nama:</strong> {{$data->name}}</p>
        <p><strong>Tanggal Pembayaran:</strong> {{$data->payment_date}}</p>
        <p><strong>Virtual Account:</strong> {{$data->virtual_account_number}}</p>
    </div>

    <div class="payment-details">
        <p><strong>Total Pembayaran:</strong> Rp. {{number_format($data->total_payment_form)}}</p>
        <p><strong>Metode Pembayaran:</strong> BANK {{$data->payment_option}}</p>
    </div>
</div>
