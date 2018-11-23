<div class="form-group">
    <div class="col-sm-4 region-container">
        <label for="location[locationCount][administrative][administrativeCount][region]" class="control-label">@lang('lite/elementForm.region')</label>
        <select class="region" id="location[locationCount][administrative][administrativeCount][region]" name="location[locationCount][administrative][administrativeCount][region]">
            <option value="" selected="selected">Select one of the following options</option>
            <option value="Bardogariya">Bardogariya</option>
            <option value="Bhajani">Bhajani</option>
            <option value="Dhangadi">Dhangadi</option>
            <option value="Chure">Chure</option>
            <option value="Ghodaghodi">Ghodaghodi</option>
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