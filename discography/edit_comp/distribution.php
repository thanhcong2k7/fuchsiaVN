
<div class="tab-pane card mb-0" id="dist">
    <div class="card-header">
        <i class="zmdi zmdi-store"></i> Distribution Outlets & Options
    </div>
    <div class="card-body">
        <!-- Main Store Selection -->
        <div class="row">
            <div class="col-12">
                <label for="stores">Digital Service Providers (DSPs)</label>
                <div class="row align-items-center no-gutters">
                    <div class="col">
                        <select name="stores[]" class="form-control" id="stores" multiple
                            data-placeholder="Choose stores/services to distribute to"
                            style="width:100%;">
                            <?php
                            $availableStores = getStore(); // Assume returns array of store objects (id, name)
                            $selectedStores = $release->stores ?? []; // Assume $release->stores is an array of selected store IDs
                            if ($availableStores) {
                                foreach ($availableStores as $s) {
                                    $isSelected = in_array($s->id, $selectedStores) ? 'selected' : '';
                                    echo '<option value="' . $s->id . '" ' . $isSelected . '>' . htmlspecialchars($s->name, ENT_QUOTES, 'UTF-8') . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-auto pl-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                id="toggleSelectAllStores" title="Select/Deselect All">
                            <label class="form-check-label small" for="toggleSelectAllStores">
                                All
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <h5>Additional Distribution Services</h5>

        <!-- Distribution Services - Organized in Cards -->
        <div class="row">
            <!-- Content ID & Monetization Services -->
            <div class="col">
                <div class="card mb-3">
                    <div class="card-header bg-transparent">
                        <i class="zmdi zmdi-collection-video"></i> Content ID & Monetization
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning callout mb-3" role="alert">
                            <i class="zmdi zmdi-info-outline"></i>
                            <strong>Note:</strong> Enable Content ID only for 100% original content
                            with properly licensed samples.
                        </div>
                        
                        <!-- YouTube Content ID -->
                        <div class="form-group">
                            <div class="icheck-material-white">
                                <input type="checkbox" id="ytcid" name="ytcid" value="1"
                                    <?php echo !empty($release->ytcid) ? "checked" : ""; ?> />
                                <label for="ytcid"> YouTube Content ID</label>
                            </div>
                        </div>
                        
                        <!-- SoundCloud Monetization -->
                        <div class="form-group">
                            <div class="icheck-material-white">
                                <input type="checkbox" id="scloud" name="scloud" value="1"
                                    <?php echo !empty($release->sc) ? "checked" : ""; ?> />
                                <label for="scloud">
                                    SoundCloud Monetization & Content Protection
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Distribution Button -->
        <div class="row mt-3">
            <div class="col-12">
                <input name="distform" id="distform" type="submit"
                    class="btn btn-warning btn-round px-5" value="Distribute Now">
            </div>
        </div>
    </div>
</div>