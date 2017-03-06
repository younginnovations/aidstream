<div class="form-group">
    <div class="col-sm-4 region-container">
        <label for="location[locationCount][administrative][administrativeCount][region]" class="control-label">@lang('lite/elementForm.region')</label>
        <select class="region" id="location[locationCount][administrative][administrativeCount][region]" name="location[locationCount][administrative][administrativeCount][region]">
            <option value="" selected="selected">Select one of the following options</option>
            <option value="Arusha">Arusha</option>
            <option value="Dar es Salaam">Dar es Salaam</option>
            <option value="Dodoma">Dodoma</option>
            <option value="Geita">Geita</option>
            <option value="Iringa">Iringa</option>
            <option value="Kagera">Kagera</option>
            <option value="Kaskazini Pemba">Kaskazini Pemba</option>
            <option value="kaskazini Unguja">Kaskazini Unguja</option>
            <option value="Katavi">Katavi</option>
            <option value="Kigoma">Kigoma</option>
            <option value="Kilimanjaro">Kilimanjaro</option>
            <option value="Kusini Pemba">Kusini Pemba</option>
            <option value="Kusini Unguja">Kusini Unguja</option>
            <option value="Lindi">Lindi</option>
            <option value="Manyara">Manyara</option>
            <option value="Mara">Mara</option>
            <option value="Mbeya">Mbeya</option>
            <option value="Mjini Magharibi">Mjini Magharibi</option>
            <option value="Morogoro">Morogoro</option>
            <option value="Mtwara">Mtwara</option>
            <option value="Mwanza">Mwanza</option>
            <option value="Njombe">Njombe</option>
            <option value="Pwani">Pwani</option>
            <option value="Rukwa">Rukwa</option>
            <option value="Ruvuma">Ruvuma</option>
            <option value="Shinyanga">Shinyanga</option>
            <option value="Simiyu">Simiyu</option>
            <option value="Singida">Singida</option>
            <option value="Tabora">Tabora</option>
            <option value="Tanga">Tanga</option>
        </select>
    </div>
    <div class="col-sm-4 district-container">
        <label for="location[locationCount][administrative][administrativeCount][district]" class="control-label">District</label>
        <select class="district" id="location[locationCount][administrative][administrativeCount][district]" name="location[locationCount][administrative][administrativeCount][district]">
            <option value="" selected="selected">Select one of the following options</option>
        </select>
    </div>
    <div class="location-wrap">
        <div class="form-group map-location">
            <button class="form-group view_map" type="button">Use map</button>
        </div>
        <div class="collection_form point">
        <label for="location[locationCount][administrative][administrativeCount][point]" class="control-label"></label>
        <div class="form-group">
            <div class="col-sm-6 hidden">
                <label for="location[locationCount][administrative][administrativeCount][point][0][latitude]" class="control-label">Latitude</label>
                <input class="latitude" name="location[locationCount][administrative][administrativeCount][point][0][latitude]" type="text" id="location[locationCount][administrative][administrativeCount][point][0][latitude]">
            </div>
            <div class="col-sm-6 hidden">
                <label for="location[locationCount][administrative][administrativeCount][point][0][longitude]" class="control-label">Longitude</label>
                <input class="longitude" name="location[locationCount][administrative][administrativeCount][point][0][longitude]" type="text" id="location[locationCount][administrative][administrativeCount][point][0][longitude]">
            </div>
            <div class="form-group full-width-wrap">
                <div class="map_container" id="location[locationCount][administrative][administrativeCount][point][0][map]" style="height: 400px; display:none"></div>
            </div>
        </div>
    </div>
    </div>
    <button class="remove_from_collection" type="button">Remove This</button>
</div>