<div class="section-contact">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-4 offset-sm-1">
				<div class="headline">
					<br>
					<h4>Silakan anda menghubungi kami lewat nomor telepon atau email di bawah ini jika ada kesulitan registrasi SPMB.</h4>
					<br><br>
					<h2>Kampus Santa Maria</h2>
					<p>Jl. Raya Darmo No 49<br>Surabaya, Jawa Timur</p>
				</div>
            </div>
            @foreach (\App\Models\Unit::all() as $u)
                @if (($loop->index+1) % 4 === 1)
                    <div class="col-sm-2">
                        <br><br>
                @endif
                    <div class="contact-headline">
                        <h4>{!! $u->name !!}</h4>
                        <p>{!! \App\Helpers\Helper::phoneWithLeadingZero($u->phone) !!}<br><small>{!! $u->email !!}</small></p>
                    </div>
                @if (($loop->index+1) % 4 ===0 || $loop->last)
                    </div>
                @endif
            @endforeach
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="footer">
					<p>SPMB Online Kampus Santa Maria @ {{ date('Y') }}</p>
				</div>
			</div>
		</div>
	</div>
</div>
