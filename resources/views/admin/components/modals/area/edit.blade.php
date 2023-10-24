@if (admin_permission_by_name("admin.area.update"))
    <div id="edit-area" class="mfp-hide large">
        <div class="modal-data">
            <div class="modal-header px-0">
                <h5 class="modal-title">{{ __("Edit Area") }}</h5>
            </div>
            <div class="modal-form-data">
                <form class="modal-form" method="POST" action="{{ setRoute('admin.area.update') }}">
                    @csrf
                    @method("PUT")
                    <input type="hidden" name="target" value="{{ old('target') }}">
                    <div class="row mb-10-none mt-2">
                        <div class="col-xl-12 col-lg-12 form-group">
                            @include('admin.components.form.input',[
                                'label'         => 'Name*',
                                'name'          => 'edit_name',
                                'value'         => old('edit_name')
                            ])
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group d-flex align-items-center justify-content-between mt-4">
                            <button type="button" class="btn btn--danger modal-close">{{ __("Cancel") }}</button>
                            <button type="submit" class="btn btn--base">{{ __("Update") }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push("script")
        <script>
            openModalWhenError("edit-area","#edit-area");
            $(".edit-modal-button").click(function(){
                var oldData = JSON.parse($(this).parents("tr").attr("data-item"));
                var editModal = $("#edit-area");
 
                editModal.find("form").first().find("input[name=target]").val(oldData.id);
                editModal.find("input[name=edit_name]").val(oldData.name);
                openModalBySelector("#edit-area");
            });
        </script>
    @endpush
@endif



