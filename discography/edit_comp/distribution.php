<div class="tab-pane card mb-0" id="dist">
    <div class="card-header">
        <i class="zmdi zmdi-store"></i> Distribution Outlets & Options
    </div>
    <div class="card-body">
        <div class="alert alert-warning callout mb-3" role="alert">
            <i class="zmdi zmdi-info-outline"></i>
            <strong>Note:</strong> Enable Content ID only for 100% original content
            with properly licensed samples.
        </div>
        <!-- Main Store Selection -->
        <div class="row">
            <div class="col-12">
                <label for="stores">Digital Service Providers (DSPs)</label>
                <div class="row align-items-center no-gutters" style="padding-left: 50px; padding-right: 50px; padding-top: 10px">
                    <?php
                    $availableStores = getStore(); // Assume returns array of store objects (id, name)
                    $selectedStores = $release->stores ?? []; // Assume $release->stores is an array of selected store IDs
                    if ($availableStores) {
                        foreach ($availableStores as $s) {
                            $isSelected = in_array($s->id, $selectedStores) ? 'selected' : '';
                            echo '<div class="col-md-6 card-body" style="padding:6px">
                <div class="icheck-material-white">
                    <input type="checkbox" id="' . $s->id . '" name="remember" checked="' . $isSelected . '" />
                    <label for="' . $s->id . '">' . htmlspecialchars($s->name, ENT_QUOTES, 'UTF-8') . '
                    </label></div></div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <hr>
        <!-- Distribution Button -->
        <div class="row mt-3">
            <div class="col-12">
                <input name="distform" id="distform" type="submit" class="btn btn-warning btn-round px-5"
                    value="Distribute Now">
            </div>
        </div>
    </div>
</div>