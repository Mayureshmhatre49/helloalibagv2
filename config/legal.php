<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Legal / Policy Details
    |--------------------------------------------------------------------------
    |
    | Used by the Privacy Policy and Terms of Service pages. Set the real
    | registered business name and the named grievance officer here — the
    | IT Act 2000 rules and the DPDP Act 2023 both expect a contactable
    | person to be published, not just a generic inbox.
    |
    */

    'entity_name' => env('LEGAL_ENTITY_NAME', 'Hello Alibaug'),

    'grievance_officer' => env('LEGAL_GRIEVANCE_OFFICER', 'The Grievance Officer'),

    'contact_email' => env('LEGAL_CONTACT_EMAIL', 'hello@helloalibaug.com'),

];
