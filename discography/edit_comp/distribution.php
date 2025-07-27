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
        <input type="text" id="chosestore" name="chosestore" style="display: none" value="">
        <div class="row">
            <div class="col-12">
                <label for="stores">Digital Service Providers (DSPs)</label>
                <div class="row align-items-center no-gutters"
                    style="padding-left: 50px; padding-right: 50px; padding-top: 10px">
                    <?php
                    $availableStores = getStore(1); // Assume returns array of store objects (id, name)
                    $selectedStores = $release->store; // Assume $release->stores is an array of selected store IDs
                    //echo json_encode($selectedStores);
                    //echo ($release->store);$tmp5 = "[]";
                    $tmp6 = query("select * from storereq where id=" . strval(2) . ";");
                    while ($row2 = $tmp6->fetch_assoc())
                        $tmp5 = $row2["data"];
                    $selectedStores = json_decode($tmp5);
                    if ($availableStores) {
                        foreach ($availableStores as $s) {
                            $isSelected = in_array($s->id, $selectedStores) ? 'checked' : '';
                            echo '<div class="col-md-6 card-body" style="padding:6px">
                                <div class="icheck-material-white">
                                    <input type="checkbox" id="' . $s->id . '" name="remember" ' . $isSelected . ' value="' . $s->id . '" />
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
            <button onclick="trig()"></button>
        </div>

        <script>
            function getCheckedCheckboxValues() {
                const checkedCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked');
                const values = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);
                return values;
            }
            function trig(){document.getElementById("chosestore").value = JSON.stringify(getCheckedCheckboxValues());console.log(document.getElementById("chosestore").value);}
            
        </script>
    </div>
</div>