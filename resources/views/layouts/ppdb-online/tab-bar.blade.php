<div class="row">
    <div class="tab-bar">
        <a href="{{route('ppdb.welcome')}}" class="tab-bar-item text-title-2 {{ isset($nav) ? ($nav['child'] == 'Home' || $nav['child'] == 'Informasi PPDB' ? 'active' : '' ) : '' }}">
            <span>Informasi SPMB</span>
            <div class="bottom-line"></div>
        </a>
        <a href="{{route('ppdb.faq-ppdb')}}" class="tab-bar-item text-title-2 {{ isset($nav) ? ($nav['child']=='FAQ'?'active':'') : '' }}">
            <span>FAQ</span>
            <div class="bottom-line"></div>
        </a>
        <!-- <a href="{{route('ppdb.notifikasi-ppdb')}}" class="tab-bar-item text-title-2 {{ isset($nav) ? ($nav['child']=='Notifikasi'?'active':'') : '' }}">
            <span>Notifikasi</span>
            <div class="bottom-line"></div>
        </a> -->
    </div>
</div>