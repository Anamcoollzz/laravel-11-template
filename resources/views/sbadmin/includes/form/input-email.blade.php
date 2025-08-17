@include('includes.form.input', ['id' => $id ?? 'email', 'type' => 'email', 'label' => $label ?? __('Email'), 'required' => $required ?? true, 'icon' => 'fas fa-envelope'])
