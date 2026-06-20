<!-- START SIDEBAR -->
<div class="sidebar clearfix" style="overflow-y: auto;">

    <div class="sidebar-logo">
        <a href="{{ url('/administrator/dashboard/') }}" class="logo"><img src="{{ asset('img/Sanmaru Logo.png') }}"></a>
        <div class="welcome">Selamat Datang <strong>{{ Auth::user()->name }}</strong>,</div>
    </div>

    <ul class="sidebar-panel sidebar-nav">
        <li class="sidetitle">MENU</li>
        <li><a href="{{ route('admin.dashboard.index') }}" class="{{ $nav['child'] == 'dashboard' ? 'active' : '' }}">
                <span class="icon color5"><i class="fa fa-home"></i></span>Dasbor</a>
        </li>
        <li><a href="{{ route('admin.dashboard-ppdb.index') }}"
                class="{{ $nav['child'] == 'dashboard-ppdb' ? 'active' : '' }}">
                <span class="icon color5"><i class="fa fa-home"></i></span>Dashboard PPDB</a>
        </li>

        @if (\App\Helpers\Helper::isPpdbRole())
            <li class="treeview {{ $nav['parent'] == 'ppdb' ? 'active menu-open' : '' }}">
                <a href="#" class="{{ $nav['parent'] == 'ppdb' ? 'active' : '' }}">
                    <span class="icon color12"><i class="fa fa-users"></i></span>PPDB
                    <span class="pull-right-container"><i class="fa fa-angle-right"></i></span></a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.ppdb.index') }}"
                            class="{{ $nav['child'] == 'ppdb' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-user-plus"></i></span>Pendaftar</a></li>
                    <li><a href="{{ route('admin.ppdb-monitoring.index') }}"
                            class="{{ $nav['child'] == 'ppdb-monitoring' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-tachometer"></i></span>Monitoring PPDB</a></li>
                    <li><a href="{{ route('admin.period.index') }}"
                            class="{{ $nav['child'] == 'period' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-clock-o"></i></span>Periode</a></li>
                    <li><a href="{{ route('admin.age-limit.index') }}"
                            class="{{ $nav['child'] == 'age-limit' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-child"></i></span>Batas Umur</a></li>
                    <li><a href="{{ route('admin.stage.index') }}"
                            class="{{ $nav['child'] == 'stage' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-align-center"></i></span>Tahap Selanjutnya</a>
                    </li>
                    <li><a href="{{ route('admin.export-data.index') }}"
                            class="{{ $nav['child'] == 'export-data' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-file"></i></span>Export Data</a></li>
                    <li><a href="{{ route('admin.payment.index') }}"
                            class="{{ $nav['child'] == 'payment' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-dollar"></i></span>Cek Pembayaran</a></li>
                    <li><a href="{{ route('admin.check-order.index') }}"
                            class="{{ $nav['child'] == 'check-order' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-cart-arrow-down"></i></span>Cek Pesanan</a></li>
                    <li><a href="{{ route('admin.ppdb-resignation.index') }}"
                            class="{{ $nav['child'] == 'ppdb-resignation' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-user-times"></i></span>Pengunduran Diri</a></li>
                    <li><a href="{{ route('admin.erp-posting.index') }}"
                            class="{{ $nav['child'] == 'erp-posting' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-refresh"></i></span>Posting PPDB ke ERP</a></li>
                    <li><a href="{{ route('admin.notification.index') }}"
                            class="{{ $nav['child'] == 'notification' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-bell"></i></span>Notification</a></li>
                    <li><a href="{{ route('admin.custom_form.index') }}"
                            class="{{ $nav['child'] == 'custom-form' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-table"></i></span>Custom Form</a></li>
                </ul>
            </li>
        @endif

        @if (\App\Helpers\Helper::isPpdbRole())
            <li class="treeview {{ $nav['parent'] == 'master' ? 'active menu-open' : '' }}">
                <a href="#" class="{{ $nav['parent'] == 'master' ? 'active' : '' }}">
                    <span class="icon color12"><i class="fa fa-database"></i></span>Master
                    <span class="pull-right-container"><i class="fa fa-angle-right"></i></span></a>
                <ul class="treeview-menu">
                    @if (\App\Helpers\Helper::isAdminRole())
                        <li><a href="{{ route('admin.course.index') }}"
                                class="{{ $nav['child'] == 'course' ? 'active' : '' }}"><span class="icon color12"><i
                                        class="fa fa-tasks"></i></span>Mata Pelajaran</a></li>
                        <li><a href="{{ route('admin.student.index') }}"
                                class="{{ $nav['child'] == 'student' ? 'active' : '' }}"><span class="icon color12"><i
                                        class="fa fa-graduation-cap"></i></span>Siswa</a></li>
                        <li><a href="{{ route('admin.teacher.index') }}"
                                class="{{ $nav['child'] == 'teacher' ? 'active' : '' }}"><span class="icon color12"><i
                                        class="fa fa-user"></i></span>Guru</a></li>
                    @endif
                    @if (\App\Helpers\Helper::isPpdbRole())
                        <li><a href="{{ route('admin.unit.index') }}"
                                class="{{ $nav['child'] == 'unit' ? 'active' : '' }}"><span class="icon color12"><i
                                        class="fa fa-university"></i></span>Unit</a></li>
                    @endif
                    @if (\App\Helpers\Helper::isAdminRole())
                        <li><a href="{{ route('admin.event.index') }}"
                                class="{{ $nav['child'] == 'event' ? 'active' : '' }}"><span class="icon color12"><i
                                        class="fa fa-calendar"></i></span>Berita</a></li>
                        <li><a href="{{ route('admin.class.index') }}"
                                class="{{ $nav['child'] == 'class' ? 'active' : '' }}"><span class="icon color12"><i
                                        class="fa fa-home"></i></span>Kelas</a></li>
                        <li><a href="{{ route('admin.extracurricular.index') }}"
                                class="{{ $nav['child'] == 'extracurricular' ? 'active' : '' }}"><span
                                    class="icon color12"><i class="fa fa-book"></i></span>Ekstrakurikuler</a></li>
                        <li><a href="{{ route('admin.finance.index') }}"
                                class="{{ $nav['child'] == 'finance' ? 'active' : '' }}"><span
                                    class="icon color12"><i class="fa fa-money"></i></span>Keuangan</a></li>
                        <li><a href="{{ route('admin.class-schedule.index') }}"
                                class="{{ $nav['child'] == 'class-schedule' ? 'active' : '' }}"><span
                                    class="icon color12"><i class="fa fa-calendar"></i></span>Jadwal Pelajaran
                                Siswa</a>
                        </li>
                    @endif
                </ul>
            </li>

        @endif

        @if (\App\Helpers\Helper::isAuthorEditorRole())
            <li class="treeview {{ $nav['parent'] == 'konten' ? 'active menu-open' : '' }}">
                <a href="#" class="{{ $nav['parent'] == 'konten' ? 'active' : '' }}">
                    <span class="icon color12"><i class="fa fa-newspaper-o"></i></span>Konten
                    <span class="pull-right-container"><i class="fa fa-angle-right"></i></span></a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.dashboard.analytic') }}"
                            class="{{ $nav['child'] == 'dashboard-analytic' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-book"></i></span>Dasbor</a></li>
                    <li><a href="{{ route('admin.blog-category.index') }}"
                            class="{{ $nav['child'] == 'blog-category' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-book"></i></span>Blog Kategori</a></li>
                    <li><a href="{{ route('admin.blog.index') }}"
                            class="{{ $nav['child'] == 'blog' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-pencil"></i></span>Blog</a></li>
                    <li><a href="{{ route('admin.campus.select') }}"
                            class="{{ $nav['child'] == 'campus' ? 'active' : '' }}"><span class="icon color12"><i
                                    class="fa fa-institution"></i></span>Kampus</a></li>
                    <li><a href="{{ route('admin.headline.index') }}"
                            class="{{ $nav['child'] == 'headline' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-image"></i></span>Headline</a></li>
                    <li><a href="{{ route('admin.gallery.index') }}"
                            class="{{ $nav['child'] == 'gallery' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-camera"></i></span>Gallery</a></li>
                    <li><a href="{{ route('admin.voice-of-sanmar.index') }}"
                            class="{{ $nav['child'] == 'voice-of-sanmar' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-music"></i></span>Voice Of Sanmar</a></li>
                    <li><a href="{{ route('admin.about.select-category') }}"
                            class="{{ $nav['child'] == 'about' || $nav['child'] == 'about-category' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-info"></i></span>About</a></li>
                    <li><a href="{{ route('admin.school-life.select-category') }}"
                            class="{{ $nav['child'] == 'school-life' || $nav['child'] == 'school-life-category' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-plus"></i></span>School Life</a></li>
                    <li><a href="{{ route('admin.testimonial.index') }}"
                            class="{{ $nav['child'] == 'testimonial' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-comment"></i></span>Testimonial</a></li>
                    <li><a href="{{ route('admin.scholarship.index') }}"
                            class="{{ $nav['child'] == 'scholarship' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-graduation-cap"></i></span>Beasiswa</a></li>
                    <li><a href="{{ route('admin.faq.index') }}"
                            class="{{ $nav['child'] == 'faq' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-comments"></i></span>Pertanyaan (FAQ)</a></li>
                    <li><a href="{{ route('admin.popup.index') }}"
                            class="{{ $nav['child'] == 'popup' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-spinner"></i></span>Popup</a></li>
                    <li><a href="{{ route('admin.facility-category.index') }}"
                            class="{{ $nav['child'] == 'facility-category' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-check-square-o"></i></span>Facility
                            Category</a>
                    </li>
                    <li><a href="{{ route('admin.facility.index') }}"
                            class="{{ $nav['child'] == 'facility' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-check"></i></span>Facility</a></li>
                    <li><a href="{{ route('admin.user-activity.index') }}"
                            class="{{ $nav['child'] == 'user-activity' ? 'active' : '' }}">
                            <span class="icon color12"><i class="fa fa-circle-o"></i></span>User Activity</a></li>
                </ul>
            </li>
        @endif

        @if (\App\Helpers\Helper::isShopRole() || \App\Helpers\Helper::isPpdbRole())
            <li class="treeview {{ $nav['parent'] == 'shop' ? 'active menu-open' : '' }}">
                <a href="#" class="{{ $nav['parent'] == 'shop' ? 'active' : '' }}">
                    <span class="icon color12"><i class="fa fa-shopping-cart"></i></span>SHOP
                    <span class="pull-right-container"><i class="fa fa-angle-right"></i></span></a>
                <ul class="treeview-menu">
                    @if (\App\Helpers\Helper::isShopRole())
                        <li><a href="{{ route('admin.dashboard-order.index') }}"
                                class="{{ $nav['child'] == 'dashboard-order' ? 'active' : '' }}"><span
                                    class="icon color12"><i class="fa fa-home"></i></span>Dasbor</a></li>
                        <li><a href="{{ route('admin.product-order.index') }}"
                                class="{{ $nav['child'] == 'product-order' ? 'active' : '' }}"><span
                                    class="icon color12"><i class="fa fa-cart-plus"></i></span>Pesanan</a></li>
                        <li><a href="{{ route('admin.vendor.index') }}"
                                class="{{ $nav['child'] == 'vendor' ? 'active' : '' }}"><span class="icon color12"><i
                                        class="fa fa-bookmark-o"></i></span>Vendor</a></li>
                        <li><a href="{{ route('admin.product.index') }}"
                                class="{{ $nav['child'] == 'product' ? 'active' : '' }}"><span
                                    class="icon color12"><i class="fa fa-shirtsinbulk"></i></span>Produk</a></li>
                        <li><a href="{{ route('admin.product-acceptance.index') }}"
                                class="{{ $nav['child'] == 'product-acceptance' ? 'active' : '' }}"><span
                                    class="icon color12"><i class="fa fa-shirtsinbulk"></i></span>Update Stock</a>
                        </li>
                        <li><a href="{{ route('admin.fitting.index') }}"
                                class="{{ $nav['child'] == 'fitting' ? 'active' : '' }}"><span
                                    class="icon color12"><i class="fa fa-clock-o"></i></span>Jadwal Fitting</a></li>
                        <li><a href="{{ route('admin.voucher.index') }}"
                                class="{{ $nav['child'] == 'voucher' ? 'active' : '' }}"><span
                                    class="icon color12"><i class="fa fa-gift"></i></span>Voucher</a></li>
                        <li><a href="{{ route('admin.uniform-payment.index') }}"
                                class="{{ $nav['child'] == 'uniform-payment' ? 'active' : '' }}"><span
                                    class="icon color12"><i class="fa fa-dollar"></i></span>Cek pembayaran</a></li>
                    @endif
                    @if (\App\Helpers\Helper::isShopRole() || \App\Helpers\Helper::isPpdbRole())
                        <li><a href="{{ route('admin.product-order-pickup.index') }}"
                                class="{{ $nav['child'] == 'product-order-pickup' ? 'active' : '' }}"><span
                                    class="icon color12"><i class="fa fa-cart-plus"></i></span>Pengambilan Pesanan</a>
                        </li>
                    @endif
                    @if (\App\Helpers\Helper::isShopRole())
                        <li><a href="{{ route('admin.uniform-overpayment.index') }}"
                                class="{{ $nav['child'] == 'uniform-overpayment' ? 'active' : '' }}"><span
                                    class="icon color12"><i class="fa fa-cart-plus"></i></span>Kelebihan Bayar</a>
                        </li>
                        <li><a href="{{ route('admin.uniform-deadline.index') }}"
                                class="{{ $nav['child'] == 'uniform-deadline' ? 'active' : '' }}"><span
                                    class="icon color12"><i class="fa fa-spinner"></i></span>Deadline Seragam</a></li>
                    @endif

                    {{-- @if (\App\Helpers\Helper::isShopRole())
                        <li><a href="{{ route('admin.product-order.index') }}"
                               class="{{($nav['child']=='product-order')?'active':''}}"><span
                                    class="icon color12"><i class="fa fa-cart-plus"></i></span>Pesanan</a></li>
                    @endif --}}

                    <li><a href="{{ route('admin.distribution-order.index') }}"
                            class="{{ $nav['child'] == 'distribution-order' ? 'active' : '' }}"><span
                                class="icon color12"><i class="fa fa-sitemap"></i></span>Distribusi Pesanan</a></li>
                    <li><a href="{{ route('admin.voucher.usage') }}"
                            class="{{ $nav['child'] == 'usage' ? 'active' : '' }}"><span class="icon color12"><i
                                    class="fa fa-sitemap"></i></span>Laporan Klaim Voucher</a></li>
                    <li><a href="{{ route('admin.complaint.index') }}"
                            class="{{ $nav['child'] == 'complaint' ? 'active' : '' }}"><span class="icon color12"><i
                                    class="fa fa-comments"></i></span>Komplain Seragam</a></li>
                </ul>
            </li>
        @endif

        @if (\App\Helpers\Helper::isShopRole() || \App\Helpers\Helper::isPpdbRole())
            <li class="treeview {{ $nav['parent'] == 'report' ? 'active menu-open' : '' }}">
                <a href="#" class="{{ $nav['parent'] == 'report' ? 'active' : '' }}">
                    <span class="icon color12"><i class="fa fa-file-pdf-o"></i></span>Report
                    <span class="pull-right-container"><i class="fa fa-angle-right"></i></span></a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.report.development-report.index') }}"
                            class="{{ $nav['child'] == 'development-fee' ? 'active' : '' }}"><span
                                class="icon color12"><i class="fa fa-money"></i></span>Laporan Dana Pengembangan</a>
                    </li>
                    <li><a href="{{ route('admin.report.admission-report.index') }}"
                            class="{{ $nav['child'] == 'admission-report' ? 'active' : '' }}"><span
                                class="icon color12"><i class="fa fa-user-plus"></i></span>Laporan Penerimaan Siswa
                            Baru</a>
                    </li>
                    <li><a href="{{ route('admin.report.payment-ppdb-report.index') }}"
                            class="{{ $nav['child'] == 'payment-ppdb-report' ? 'active' : '' }}"><span
                                class="icon color12"><i class="fa fa-credit-card"></i></span>Laporan Pembayaran
                            PPDB</a>
                    </li>
                    <li><a href="{{ route('admin.report.dispensation-report.index') }}"
                            class="{{ $nav['child'] == 'dispensation-report' ? 'active' : '' }}"><span
                                class="icon color12"><i class="fa fa-credit-card"></i></span>Laporan Penerima
                            Dispensasi</a>
                    </li>
                </ul>
            </li>
        @endif

        @if (\App\Helpers\Helper::isPpdbRole())
            <li class="treeview {{ $nav['parent'] == 'finance-configuration' ? 'active menu-open' : '' }}">
                <a href="#" class="{{ $nav['parent'] == 'finance-configuration' ? 'active' : '' }}">
                    <span class="icon color12"><i class="fa fa fa-money"></i></span>Keuangan
                    <span class="pull-right-container"><i class="fa fa-angle-right"></i></span></a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.system-configuration.index') }}"
                            class="{{ $nav['child'] == 'system-configuration' ? 'active' : '' }}"><span
                                class="icon color12"><i class="fa fa-sliders"></i></span>Konfigurasi
                            Pembiayaan</a></li>

                    <li><a href="{{ route('admin.dispensation-request.index') }}"
                            class="{{ $nav['child'] == 'dispensation-request' ? 'active' : '' }}"><span
                                class="icon color12"><i class="fa fa-reply-all"></i></span>Pengajuan Dispensasi
                            Siswa</a></li>

                    <li><a href="{{ route('admin.dispensation.index') }}"
                            class="{{ $nav['child'] == 'dispensations' ? 'active' : '' }}"><span
                                class="icon color12"><i class="fa fa-calculator"></i></span>Kelola Dispensasi</a>
                    </li>

                </ul>
            </li>
        @endif

    </ul>

    @if (\App\Helpers\Helper::isSuperAdminRole())
        <ul class="sidebar-panel sidebar-nav">
            <li class="sidetitle">DEPLOYMENT</li>
            <li><a href="{{ route('admin.deploy.index') }}"><span class="icon color12"><i
                            class="fa fa-cloud-upload"></i></span>Deploy</a></li>
        </ul>
    @endif

    <ul class="sidebar-panel sidebar-nav">
        <li class="sidetitle">AKUN</li>
        @if (\App\Helpers\Helper::isAdminRole())
            <li>
                <a href="{{ route('admin.user.index') }}"
                    class="{{ $nav['child'] == 'user' ? 'active' : '' }}"><span class="icon color12"><i
                            class="fa fa-users"></i></span>Pengguna</a>
            </li>
        @endif
        <li>
            <a href="{{ route('admin.logout') }}"><span class="icon color12"><i
                        class="fa fa-power-off"></i></span>Keluar</a>
        </li>
    </ul>

</div>
<!-- END SIDEBAR -->
@push('scripts')
    <script>
        $(document).ready(function() {
            $(".sidebar-nav").on('click', '.treeview a', function(e) {
                var treeViewMenu = $(this).next('.treeview-menu');
                var parentLi = $(this).parent();
                var isOpen = parentLi.hasClass('menu-open');

                var collapsedEvent = $.Event(Event.collapsed);
                var expandedEvent = $.Event(Event.expanded);

                if (!parentLi.is('.treeview')) {
                    return;
                }

                if (isOpen) {
                    parentLi.removeClass('menu-open');
                    treeViewMenu.stop().slideUp(500, function() {
                        $(treeViewMenu.element).trigger(collapsedEvent);
                        parentLi.find('.treeview').removeClass('menu-open').find('.treeview-menu')
                            .hide();
                        parentLi.height('auto');
                    });
                } else {
                    var openMenuLi = parentLi.siblings('.menu-open');
                    var openTree = openMenuLi.children('.treeview-menu');

                    openMenuLi.removeClass('menu-open');
                    openTree.stop().slideUp(500, function() {
                        $(openTree.element).trigger(collapsedEvent);
                        openMenuLi.find('.treeview').removeClass('menu-open').find('.treeview-menu')
                            .hide();
                    });

                    parentLi.addClass('menu-open');
                    treeViewMenu.slideDown(500, function() {
                        $(treeViewMenu.element).trigger(expandedEvent);
                        parentLi.height('auto');
                    });
                }
            });
        });
    </script>
@endpush
