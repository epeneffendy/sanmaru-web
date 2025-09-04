@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Konten / Dashboard</h1>
        <ol class="breadcrumb">
            <li class="active">Gambaran umum rekapitulasi data pengunjung website</li>
        </ol>
    </div>

    <div class="container-padding" style="padding-bottom: 50px;">
      {{-- <div class="panel panel-default">
        <div class="panel-title">
          <b>Overall - {{ $date->format('d F Y') }}</b>
          <div class="pull-right">
            date 
            <input type="text" class="date" value="{{ $date->format('m/d/Y') }}" />
          </div>
        </div>
        <div class="panel-body" style="padding-top: 30px">
          <div class="row">
            <div class="col-md-10 col-md-offset-1" style="text-align: center;">
              <div class="row">
                <div class="col-md-4">
                  <div class="panel panel-danger">
                    <div class="panel-body" style="text-align: center; font-size: 28px; font-weight: 700; padding: 10px 0;">
                      {{ @$today['users'] }}
                    </div>
                    <div class="panel-footer">
                      <b>Visitor today</b>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="panel panel-primary">
                    <div class="panel-body" style="text-align: center; font-size: 28px; font-weight: 700; padding: 10px 0;">
                        {{ @$this_week['users'] }}
                    </div>
                    <div class="panel-footer">
                      <b>Visitor this week</b>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="panel panel-success">
                    <div class="panel-body" style="text-align: center; font-size: 28px; font-weight: 700; padding: 10px 0;">
                      {{ @$this_month['users'] }}
                    </div>
                    <div class="panel-footer">
                      <b>Visitor this month</b>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4 col-md-offset-2">
                  <div class="panel panel-warning">
                    <div class="panel-body" style="text-align: center; font-size: 28px; font-weight: 700; padding: 10px 0;">
                      {{ @$today['new_users'] }}
                    </div>
                    <div class="panel-footer">
                      <b>New visitors</b>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="panel panel-default">
                    <div class="panel-body" style="text-align: center; font-size: 28px; font-weight: 700; padding: 10px 0;">
                        {{ @$today['old_users'] }}
                    </div>
                    <div class="panel-footer">
                      <b>Returning visitors</b>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-title">
          <select name="change_period" id="change-period">
            <option value="7days">Last 7 days</option>
            <option value="14days">Last 14 days</option>
            <option value="28days">Last 28 days</option>
          </select>
        </div>
        <div class="panel-body" style="padding-top: 30px">
          <div class="row">
            <div class="col-md-4">
              <h4>Sessions by device</h4>

              <div class="row">
                <canvas id="devicesChart" width="400" height="400"></canvas>
              </div>
              <div class="row">
                @php
                  $devicesCollect = collect($this_week['device_category']);
                @endphp
                <div class="col-md-4" style="text-align: center;">
                  <h1><i class="fa fa-mobile"></i></h1>
                  Mobile
                  <h4>{{ number_format(@$this_week['device_category']['mobile'] / $devicesCollect->sum() * 100, 2) }}%</h4>
                </div>
                <div class="col-md-4" style="text-align: center;">
                  <h1><i class="fa fa-desktop"></i></h1>
                  Desktop
                  <h4>{{ number_format(@$this_week['device_category']['desktop'] / $devicesCollect->sum() * 100, 2) }}%</h4>
                </div>
                <div class="col-md-4" style="text-align: center;">
                  <h1><i class="fa fa-tablet"></i></h1>
                  Tablet
                  <h4>{{ number_format(@$this_week['device_category']['tablet'] / $devicesCollect->sum() * 100, 2) }}%</h4>
                </div>
              </div>
            </div>

            <div class="col-md-7 col-md-offset-1">
              <div class="panel-body">
                <div role="tabpanel">
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs nav-line" role="tablist">
                    <li role="presentation" class="active"><a href="#channel" aria-controls="channel" role="tab" data-toggle="tab">Traffic Channel</a></li>
                    <li role="presentation"><a href="#source" aria-controls="source" role="tab" data-toggle="tab">Source/Medium</a></li>
                    <li role="presentation"><a href="#referral" aria-controls="referral" role="tab" data-toggle="tab">Referrals</a></li>
                  </ul>
                  <!-- Tab panes -->
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="channel">
                      <canvas id="channelCart" width="400" height="400"></canvas>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="source">
                      <canvas id="sourceCart" width="400" height="400"></canvas>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="referral">
                      <canvas id="referralCart" width="400" height="400"></canvas>
                    </div>
                  </div>
                </div>              
              </div>
            </div>

          </div>
        </div>
      </div> --}}

      <div class="panel-body" style="padding-top: 30px">
        <iframe width="100%" height="1200"
          src="https://datastudio.google.com/embed/reporting/da7897d3-caff-4daf-a34c-9209d694ef83/page/p_e5s648elzc"
          frameborder="0" style="border:0" allowfullscreen="">
        </iframe>
      </div>

    </div>
@endsection

@push('scripts')
  <script src="{{asset('js/moment/moment.min.js')}}"></script>
  <script src="{{asset('js/date-range-picker/daterangepicker.js')}}"></script>
  <script src="{{ asset('js/chartjs/Chart.min.js') }}"></script>
  {{-- <script>

    $(document).ready(function() {
      $('.date').daterangepicker({
          singleDatePicker: true,
          showDropdowns: true,
          autoApply: true,
          locale: {
              format: 'YYYY-MM-DD',
          }
      });

      $('.date').on('apply.daterangepicker', function(ev, picker) {
          window.location.href = '{{ route('admin.dashboard.analytic') }}?date='+ picker.startDate.format('YYYY-MM-DD');
      });

      var weekDeviceCartDatas = [{{ @$this_week['device_category']['mobile']?:0 }}, {{ @$this_week['device_category']['desktop']?:0 }}, {{ @$this_week['device_category']['tablet']?:0 }}];
      var twoWeekDeviceCartDatas = [{{ @$this_week['device_category']['mobile']+@$last_week['device_category']['mobile']?:0 }}, {{ @$this_week['device_category']['desktop']+@$last_week['device_category']['desktop']?:0 }}, {{ @$this_week['device_category']['tablet']+@$last_week['device_category']['tablet']?:0 }}];
      var fourWeekDeviceCartDatas = [{{ @$this_month['device_category']['mobile']?:0 }}, {{ @$this_month['device_category']['desktop']?:0 }}, {{ @$this_month['device_category']['tablet']?:0 }}];

      var ctx = document.getElementById('devicesChart').getContext('2d');
      window.deviceCart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['Mobile', 'Desktop', 'Tablet'],
          datasets: [{
            data: weekDeviceCartDatas,
            backgroundColor: [
              'rgb(69,133,244)',
              'rgb(84,165,245)',
              'rgb(143,206,238)',
            ],
          }]
        },
        options: {
          legend: {
            display: false,
          }
        }
      });


      @php
        $endWeek = clone $date;
        $startTwoWeek = clone $date;
        $startFourWeek = clone $date;
        // week
        $startWeek = \Carbon\Carbon::parse($date->subDays(7)->format('Y-m-d'));
        // two weeks
        $startTwoWeek =  $startTwoWeek->subDays(14);
        // four weeks
        $startFourWeek = $startFourWeek->subDays(28);
        $weeks = $weeksLabel = $twoWeeks = $twoWeeksLabel = $fourWeeks = $fourWeeksLabel = [];
        $colors = [
          'rgb(82,162,236)',
          'rgb(69,133,244)',
          'rgb(84,165,245)',
          'rgb(143,206,238)',
          'rgb(95,192,192)',
          'rgb(233,96,130)'
        ];

        // weeks label
        while ($startWeek->lessThanOrEqualTo($endWeek)) {
          $weeks[] = $startWeek->format('Ymd');
          $weeksLabel[] = $startWeek->format('j F');
          $startWeek->addDay();
        }

        // two weeks label
        while ($startTwoWeek->lessThanOrEqualTo($endWeek)) {
          $twoWeeks[] = $startTwoWeek->format('Ymd');
          $twoWeeksLabel[] = $startTwoWeek->format('j F');
          $startTwoWeek->addDay();
        }

        // four weeks label
        while ($startFourWeek->lessThanOrEqualTo($endWeek)) {
          $fourWeeks[] = $startFourWeek->format('Ymd');
          $fourWeeksLabel[] = $startFourWeek->format('j F');
          $startFourWeek->addDay();
        }

        $channelCartDatasets = $sourceCartDatasets = $referralCartDatasets = [];
        $twoWeekChannelCartDatasets = $twoWeekSourceCartDatasets = $twoWeekReferralCartDatasets = [];
        $fourWeekChannelCartDatasets = $fourWeekSourceCartDatasets = $FourWeekReferralCartDatasets = [];
        $i = 0;
        foreach ($channel as $key => $c) {
          $channelCartDatas = $twoWeekChannelCartDatas = $fourWeekChannelCartDats = [];
          foreach ($weeks as $week) {
            $channelCartDatas[] = isset($channel[$key][$week]) ? $channel[$key][$week] : 0;
          }
          foreach ($twoWeeks as $twoWeek) {
            $twoWeekChannelCartDatas[] = isset($channel[$key][$twoWeek]) ? $channel[$key][$twoWeek] : 0;
          }
          foreach ($fourWeeks as $fourWeek) {
            $fourWeekChannelCartDatas[] = isset($channel[$key][$fourWeek]) ? $channel[$key][$fourWeek] : 0;
          }

          $channelCartDatasets[] = [
            'label' => $key,
            'backgroundColor' => $colors[$i%6],
            'data' => $channelCartDatas
          ];
          $twoWeekChannelCartDatasets[] = [
            'label' => $key,
            'backgroundColor' => $colors[$i%6],
            'data' => $twoWeekChannelCartDatas
          ];
          $fourWeekChannelCartDatasets[] = [
            'label' => $key,
            'backgroundColor' => $colors[$i%6],
            'data' => $fourWeekChannelCartDatas
          ];
          $i++;
        }

        $i = 0;
        foreach ($source_medium as $key => $c) {
          $sourceCartDatas = $twoWeekSourceCartDatas = $fourWeekSourceCartDatas = [];
          foreach ($weeks as $week) {
            $sourceCartDatas[] = isset($source_medium[$key][$week]) ? $source_medium[$key][$week] : 0;
          }
          foreach ($twoWeeks as $twoWeek) {
            $twoWeekSourceCartDatas[] = isset($source_medium[$key][$twoWeek]) ? $source_medium[$key][$twoWeek] : 0;
          }
          foreach ($fourWeeks as $fourWeek) {
            $fourWeekSourceCartDatas[] = isset($source_medium[$key][$fourWeek]) ? $source_medium[$key][$fourWeek] : 0;
          }

          $sourceCartDatasets[] = [
            'label' => $key,
            'backgroundColor' => $colors[$i%6],
            'data' => $sourceCartDatas
          ];
          $twoWeekSourceCartDatasets[] = [
            'label' => $key,
            'backgroundColor' => $colors[$i%6],
            'data' => $twoWeekSourceCartDatas
          ];
          $fourWeekSourceCartDatasets[] = [
            'label' => $key,
            'backgroundColor' => $colors[$i%6],
            'data' => $fourWeekSourceCartDatas
          ];
          $i++;
        }

        $i = 0;
        foreach ($referrer as $key => $c) {
          $referrerCartDatas = $twoWeekReferrerCartDatas = $fourWeekReferrerCartDatas = [];
          foreach ($weeks as $week) {
            $referrerCartDatas[] = isset($referrer[$key][$week]) ? $referrer[$key][$week] : 0;
          }
          foreach ($twoWeeks as $twoWeek) {
            $twoWeekReferrerCartDatas[] = isset($referrer[$key][$twoWeek]) ? $referrer[$key][$twoWeek] : 0;
          }
          foreach ($fourWeeks as $fourWeek) {
            $fourWeekReferrerCartDatas[] = isset($referrer[$key][$fourWeek]) ? $referrer[$key][$fourWeek] : 0;
          }

          $referralCartDatasets[] = [
            'label' => $key,
            'backgroundColor' => $colors[$i%6],
            'data' => $referrerCartDatas
          ];
          $twoWeekReferralCartDatasets[] = [
            'label' => $key,
            'backgroundColor' => $colors[$i%6],
            'data' => $twoWeekReferrerCartDatas
          ];
          $fourWeekReferralCartDatasets[] = [
            'label' => $key,
            'backgroundColor' => $colors[$i%6],
            'data' => $fourWeekReferrerCartDatas
          ];
          $i++;
        }

      @endphp

      // channel cart
      var weeksLabel = ['{!! implode("', '", $weeksLabel) !!}'];
      var weeksDatasets = {!! json_encode($channelCartDatasets) !!};
      var twoWeeksLabel = ['{!! implode("', '", $twoWeeksLabel) !!}'];
      var twoWeeksDatasets = {!! json_encode($twoWeekChannelCartDatasets) !!};
      var fourWeeksLabel = ['{!! implode("', '", $fourWeeksLabel) !!}'];
      var fourWeeksDatasets = {!! json_encode($fourWeekChannelCartDatasets) !!};

      var ctx = document.getElementById('channelCart').getContext('2d');
      window.channelCart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['{!! implode("', '", $weeksLabel) !!}'],
          datasets: {!! json_encode($channelCartDatasets) !!}
        },
        options: {
          responsive: true,
          scales: {
            xAxes: [{
              stacked: true,
            }],
            yAxes: [{
              stacked: true
            }]
          }
        }
      }); --}}

      {{-- // source cart
      var sourceCartDatasets = {!! json_encode($sourceCartDatasets) !!};
      var twoWeeksSourceCartDatasets = {!! json_encode($twoWeekSourceCartDatasets) !!};
      var fourWeeksSourceCartDatasets = {!! json_encode($fourWeekSourceCartDatasets) !!};
      var ctx = document.getElementById('sourceCart').getContext('2d');
      window.sourceCart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['{!! implode("', '", $weeksLabel) !!}'],
          datasets: {!! json_encode($sourceCartDatasets) !!}
        },
        options: {
          responsive: true,
          scales: {
            xAxes: [{
              stacked: true,
            }],
            yAxes: [{
              stacked: true
            }]
          }
        }
      });

      // channel cart
      var weeksReferrerCartDatasets = {!! json_encode($referralCartDatasets) !!};
      var twoWeeksReferrerCartDatasets = {!! json_encode($twoWeekReferralCartDatasets) !!};
      var fourWeeksReferrerCartDatasets = {!! json_encode($fourWeekReferralCartDatasets) !!};
      var ctx = document.getElementById('referralCart').getContext('2d');
      window.referralCart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['{!! implode("', '", $weeksLabel) !!}'],
          datasets: {!! json_encode($referralCartDatasets) !!}
        },
        options: {
          responsive: true,
          scales: {
            xAxes: [{
              stacked: true,
            }],
            yAxes: [{
              stacked: true
            }]
          }
        }
      });

      $(document).on('change', '#change-period', function() {
        let cartChoice = $('#change-period :selected').val();

        if (cartChoice == '7days') {
          window.deviceCart.data.datasets[0].data = weekDeviceCartDatas;
          window.deviceCart.update();

          window.channelCart.data = {
            labels: weeksLabel,
            datasets: weeksDatasets
          };
          window.channelCart.update();

          window.sourceCart.data = {
            labels: weeksLabel,
            datasets: sourceCartDatasets
          };
          window.sourceCart.update();

          window.referralCart.data = {
            labels: weeksLabel,
            datasets: weeksReferrerCartDatasets
          };
          window.referralCart.update();
        } else if (cartChoice == '14days') {
          window.deviceCart.data.datasets[0].data = twoWeekDeviceCartDatas;
          window.deviceCart.update();

          window.channelCart.data = {
            labels: twoWeeksLabel,
            datasets: twoWeeksDatasets
          };
          window.channelCart.update();

          window.sourceCart.data = {
            labels: twoWeeksLabel,
            datasets: twoWeeksSourceCartDatasets
          };
          window.sourceCart.update();

          window.referralCart.data = {
            labels: twoWeeksLabel,
            datasets: twoWeeksReferrerCartDatasets
          };
          window.referralCart.update();
        } else {
          window.deviceCart.data.datasets[0].data = fourWeekDeviceCartDatas;
          window.deviceCart.update();

          window.channelCart.data = {
            labels: fourWeeksLabel,
            datasets: fourWeeksDatasets
          };
          window.channelCart.update();

          window.sourceCart.data = {
            labels: fourWeeksLabel,
            datasets: fourWeeksSourceCartDatasets
          };
          window.sourceCart.update();

          window.referralCart.data = {
            labels: fourWeeksLabel,
            datasets: fourWeeksReferrerCartDatasets
          };
          window.referralCart.update();
        }

      });
    });


  </script> --}}
@endpush