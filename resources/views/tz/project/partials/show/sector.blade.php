<div class="activity-element-wrapper">
    <div class="activity-element-list">
        <div class="activity-element-label">
            Sector
        </div>
        <div class="activity-element-info">
            {{ $getCode->getCodeListName('Activity','SectorCategory', getVal($project->sector, [0, 'sector_category_code'])) }}
        </div>
    </div>
</div>
