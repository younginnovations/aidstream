<?php

return [
    /**
     * Path where all the generated xml files are stored in Aidstream.
     */
    'xml-file'                   => public_path('uploads/files/xml/'),
    /**
     * Path where all the uploaded documents are stored in Aidstream.
     */
    'document'                   => public_path('uploads/files/document/'),
    /**
     * Api URL for the IATI Registry.
     */
    'iati_registry_api_base_url' => 'http://iatiregistry.org/api/',
    /**
     * File format for IATI file while publishing.
     */
    'format'                     => 'IATI-XML',
    /**
     * Default file mime-type.
     */
    'mimeType'                   => 'application/xml',
    /**
     * Path to stored xml.
     */
    'xmlStorage'                 => public_path('files/xml'),
    /*
     * Dir to store xml
     */
    'xmlStorageDir'              => 'files/xml'
];
