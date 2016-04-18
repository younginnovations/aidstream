<?php
return [
    'added'                        => sprintf('Activity identifier ":identifier" added for <a href="%s">":organization - :organization_id"</a>', route('organization.show', ':organization_id')),
    'iati_identifier_updated'      => sprintf('Activity identifier ":identifier" updated for <a href="%s">":organization - :organization_id"</a>', route('organization.show', ':organization_id')),
    'other_identifier_updated'     => sprintf(
        'Other activity identifier updated  for activity id <a href="%s">":activity_id"</a> and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'title_updated'                => sprintf(
        'Activity title updated for activity id <a href="%s">":activity_id"</a> and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'planned_disbursement_updated' => sprintf(
        'Activity Planned Disbursement updated for activity id <a href="%s">":activity_id"</a> and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'budget_updated'               => sprintf(
        'Activity budget updated for activity id <a href="%s">":activity_id"</a> and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'related_activity_updated'     => sprintf(
        'Activity related activity updated for activity id <a href="%s">":activity_id"</a> and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'condition_updated'            => sprintf(
        'Activity condition updated for activity id <a href="%s">":activity_id"</a> and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'legacy_data_updated'          => sprintf(
        'Activity Legacy data updated for activity id <a href="%s">":activity_id"</a> and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'description_updated'          => sprintf(
        'Activity description updated for activity id <a href="%s">":activity_id"</a> and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'activity_status_updated'      => sprintf(
        'Activity status updated for activity_id <a href="%s">":activity_id"</a> and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'activity_date_updated'        => sprintf(
        'Activity activity date updated for activity id <a href="%s">":activity_id"</a>  and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'contact_info_updated'         => sprintf(
        'Activity contact info activity id <a href="%s">":activity_id"</a> and organization updated for <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'activity_scope_updated'       => sprintf(
        'Activity scope updated for activity id <a href="%s">":activity_id"</a> and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'participating_organization'   => sprintf(
        'Activity participating organization updated for for activity id <a href="%s">":activity_id"</a> and organization<a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'sector_updated'               => sprintf(
        'Activity sector updated for activity id <a href="%s">":activity_id"</a>  and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'policy_marker_updated'        => sprintf(
        'Activity policy marker updated for activity id <a href="%s">":activity_id"</a>  and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'recipient_country_updated'    => sprintf(
        'Activity country region updated for activity id <a href="%s">":activity_id"</a>  and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'recipient_region_updated'     => sprintf(
        'Activity recipient region updated for activity id <a href="%s">":activity_id"</a>  and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'activity_added'               => sprintf('New Activity identifier":identifier" updated for <a href="%s">":organization - :organization_id"</a>', route('organization.show', ':organization_id')),
    'activity_duplicated'          => sprintf(
        'Activity has been duplicated with activity id <a href="%s">":activity_id"</a> for <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'activity_deleted'             => sprintf(
        'Activity has been deleted with activity id <a href="%s">":activity_id"</a> from <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'step_two_completed'           => sprintf(
        'Activity step two "title" and "description" updated for activity id <a href="%s">":activity_id"</a> and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'step_three_completed'         => sprintf(
        'Activity step three "activityStatus" and "activityDate" updated for activity id <a href="%s">":activity_id"</a> and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'country_budget_items'         => sprintf(
        'Activity country budget items updated for for activity id <a href="%s">":activity_id"</a>  and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'location_updated'             => sprintf(
        'Activity Location updated for activity id <a href="%s">":activity_id"</a>  and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'document_link'                => sprintf(
        'Activity Document Link updated for activity id <a href="%s">":activity_id"</a>  and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'organization_added'           => sprintf(
        'New organization added with organization id <a href="%s">":organization_id"</a> and user id ":user_id"</a>',
        route('organization.show', ':organization_id')
    ),
    'organization_updated'         => sprintf('Organization updated with organization id <a href="%s">":organization_id"</a> and user id ":user_id"', route('organization.show', ':organization_id')),
    'collaboration_type'           => sprintf(
        'Activity Collaboration Type updated for activity id <a href="%s">":activity_id"</a>  and organization  <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'default_flow_type'            => sprintf(
        'Activity Default Flow Type updated for activity id <a href="%s">":activity_id"</a>  and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'default_finance_type'         => sprintf(
        'Activity Default Finance Type updated for activity id <a href="%s">":activity_id"</a>  and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'default_aid_type'             => sprintf(
        'Activity Default Aid Type updated for activity id <a href="%s">":activity_id"</a>  and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'default_tied_status'          => sprintf(
        'Activity Default Tied Status updated for activity id <a href="%s">":activity_id"</a>  and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'capital_spend'                => sprintf(
        'Activity Capital Spend updated for activity id <a href="%s">":activity_id"</a>  and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'result_saved'                 => sprintf(
        'Activity Result saved for Activity id <a href="%s">":activity_id"</a> and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'result_updated'               => sprintf(
        'Activity Result updated for Activity id <a href="%s">":activity_id"</a> and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'transaction_added'            => sprintf('Activity Transaction added for <a href="%s">"activity - :activity_id"</a>', route('activity.show', ':activity_id')),
    'transaction_updated'          => sprintf('Activity Transaction ":transaction_id" updated for <a href="%s">"activity - :activity_id"</a>', route('activity.show', ':activity_id')),
    'transaction_uploaded'         => sprintf('Activity Transactions are uploaded for <a href="%s">"activity - :activity_id"</a>', route('activity.show', ':activity_id')),
    'activity_uploaded'            => sprintf('Activities are uploaded for <a href="%s">":organization - :organization_id"</a>', route('organization.show', ':organization_id')),
    'activity_default_values'      => sprintf(
        'Activity default values are updated for <a href="%s">":organization - :organization_id"</a> and <a href="%s">"activity - :activity_id"</a>',
        route('organization.show', ':organization_id'),
        route('activity.show', ':activity_id')
    ),
    'humanitarian_scope_updated'   => sprintf(
        'Activity humanitarian scope updated for activity id <a href="%s">":activity_id"</a>  and organization <a href="%s">":organization - :organization_id"</a>',
        route('activity.show', ':activity_id'),
        route('organization.show', ':organization_id')
    ),
    'version_upgraded'             => sprintf('Version upgraded to ":version" for organization <a href="%s">":organization - :organization_id"</a>', route('organization.show', ':organization_id')),
    'document_saved'               => sprintf('Document saved for organization <a href="%s">":organization - :organization_id"</a>', route('organization.show', ':organization_id')),
    'document_updated'             => sprintf('Document updated for organization <a href="%s">":organization - :organization_id"</a>', route('organization.show', ':organization_id')),
    'password_reset_link'          => sprintf('Password reset link has been sent successfully to ":email"'),
    'settings_updated'             => sprintf('Settings updated for <a href="%s">":organization - :organization_id"</a>', route('organization.show', ':organization_id')),
    'activity_status_changed'      => sprintf('Activity with activity id <a href="%s">":activity_id"</a> has been marked as ":status"', route('activity.show', ':activity_id')),
];
