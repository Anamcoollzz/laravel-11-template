<div class="card">
  <div class="card-header">
    <h4><i class="fa fa-filter"></i> {{ __('Filter Data') }}</h4>
    <div class="card-header-action">
      <a class="btn btn-primary" data-toggle="collapse" href="#collapseFilterData" role="button" aria-expanded="false" aria-controls="collapseFilterData">
        <i class="fa fa-angle-down"></i>
      </a>
    </div>
  </div>
  <div class="card-body">
    <div class="collapse" id="collapseFilterData">
      <form action="">
        @csrf
        <div class="row">
          <div class="col-md-6">
            @include('stisla.includes.forms.inputs.input', [
                'required' => false,
                'name' => 'filter_start_created_at',
                'type' => 'date',
                'label' => 'Start Created At',
                'value' => request('filter_start_created_at', null),
            ])
          </div>
          <div class="col-md-6">
            @include('stisla.includes.forms.inputs.input', [
                'required' => false,
                'name' => 'filter_end_created_at',
                'type' => 'date',
                'label' => 'End Created At',
                'value' => request('filter_end_created_at', null),
            ])
          </div>
          <div class="col-md-6">
            @include('stisla.includes.forms.inputs.input', [
                'required' => false,
                'name' => 'filter_start_updated_at',
                'type' => 'date',
                'label' => 'Start Updated At',
                'value' => request('filter_start_updated_at', null),
            ])
          </div>
          <div class="col-md-6">
            @include('stisla.includes.forms.inputs.input', [
                'required' => false,
                'name' => 'filter_end_updated_at',
                'type' => 'date',
                'label' => 'End Updated At',
                'value' => request('filter_end_updated_at', null),
            ])
          </div>
          <div class="col-md-6">
            @include('stisla.includes.forms.selects.select', [
                'id' => 'filter_created_by_id',
                'name' => 'filter_created_by_id',
                'options' => $users->pluck('name', 'id')->toArray(),
                'label' => 'Created By',
                'required' => false,
                'with_all' => true,
                'selected' => request('filter_created_by_id', null),
            ])
          </div>
          <div class="col-md-6">
            @include('stisla.includes.forms.selects.select', [
                'id' => 'filter_last_updated_by_id',
                'name' => 'filter_last_updated_by_id',
                'options' => $users->pluck('name', 'id')->toArray(),
                'label' => 'Last Updated By',
                'required' => false,
                'with_all' => true,
                'selected' => request('filter_last_updated_by_id', null),
            ])
          </div>
          <div class="col-12">
            @include('stisla.includes.forms.buttons.btn-search')
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
