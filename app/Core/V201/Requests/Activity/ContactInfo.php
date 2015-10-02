<?php namespace App\Core\V201\Requests\Activity;

use App\Http\Requests\Request;

/**
 * Class ContactInfo
 * @package App\Core\V201\Requests\Activity
 */
class ContactInfo extends Request
{

    protected $redirect;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        foreach ($this->request->get('contact_info') as $contactInfoIndex => $contactInfo) {
            foreach ($contactInfo['organization'] as $organizationIndex => $organization) {
                foreach ($organization['narrative'] as $narrativeIndex => $narrative) {
                    $rules['contact_info.' . $contactInfoIndex . '.organization.' . $organizationIndex . '.narrative.' . $narrativeIndex . '.narrative'] = 'required';
                }
            }
            foreach ($contactInfo['department'] as $departmentIndex => $department) {
                foreach ($department['narrative'] as $narrativeIndex => $narrative) {
                    $rules['contact_info.' . $contactInfoIndex . '.department.' . $departmentIndex . '.narrative.' . $narrativeIndex . '.narrative'] = 'required';
                }
            }
            foreach ($contactInfo['person_name'] as $personNameIndex => $personName) {
                foreach ($personName['narrative'] as $narrativeIndex => $narrative) {
                    $rules['contact_info.' . $contactInfoIndex . '.person_name.' . $personNameIndex . '.narrative.' . $narrativeIndex . '.narrative'] = 'required';
                }
            }
            foreach ($contactInfo['job_title'] as $jobTitleIndex => $jobTitle) {
                foreach ($jobTitle['narrative'] as $narrativeIndex => $narrative) {
                    $rules['contact_info.' . $contactInfoIndex . '.job_title.' . $jobTitleIndex . '.narrative.' . $narrativeIndex . '.narrative'] = 'required';
                }
            }
            foreach ($contactInfo['mailing_address'] as $mailingAddressIndex => $mailingAddress) {
                foreach ($mailingAddress['narrative'] as $narrativeIndex => $narrative) {
                    $rules['contact_info.' . $contactInfoIndex . '.mailing_address.' . $mailingAddressIndex . '.narrative.' . $narrativeIndex . '.narrative'] = 'required';
                }
            }
            foreach ($contactInfo['telephone'] as $telephoneIndex => $telephone) {
                $rules['contact_info.' . $contactInfoIndex . '.telephone.' . $telephoneIndex . '.telephone'] = 'numeric';
            }
            foreach ($contactInfo['email'] as $emailIndex => $email) {
                $rules['contact_info.' . $contactInfoIndex . '.email.' . $emailIndex . '.email'] = 'email';
            }
        }

        return $rules;
    }

    /**
     * prepare the error message
     * @return array
     */
    public function messages()
    {
        $messages = [];
        foreach ($this->request->get('contact_info') as $contactInfoIndex => $contactInfo) {
            foreach ($contactInfo['organization'] as $organizationIndex => $organization) {
                foreach ($organization['narrative'] as $narrativeIndex => $narrative) {
                    $messages['contact_info.' . $contactInfoIndex . '.organization.' . $organizationIndex . '.narrative.' . $narrativeIndex . '.narrative' . '.required'] = 'Organization name is required';
                }
            }
            foreach ($contactInfo['department'] as $departmentIndex => $department) {
                foreach ($department['narrative'] as $narrativeIndex => $narrative) {
                    $messages['contact_info.' . $contactInfoIndex . '.department.' . $departmentIndex . '.narrative.' . $narrativeIndex . '.narrative' . '.required'] = 'Department name is required';
                }
            }
            foreach ($contactInfo['person_name'] as $personNameIndex => $personName) {
                foreach ($personName['narrative'] as $narrativeIndex => $narrative) {
                    $messages['contact_info.' . $contactInfoIndex . '.person_name.' . $personNameIndex . '.narrative.' . $narrativeIndex . '.narrative' . '.required'] = 'Person name is required';
                }
            }
            foreach ($contactInfo['job_title'] as $jobTitleIndex => $jobTitle) {
                foreach ($jobTitle['narrative'] as $narrativeIndex => $narrative) {
                    $messages['contact_info.' . $contactInfoIndex . '.job_title.' . $jobTitleIndex . '.narrative.' . $narrativeIndex . '.narrative' . '.required'] = 'Job Title is required';
                }
            }
            foreach ($contactInfo['mailing_address'] as $mailingAddressIndex => $mailingAddress) {
                foreach ($mailingAddress['narrative'] as $narrativeIndex => $narrative) {
                    $messages['contact_info.' . $contactInfoIndex . '.mailing_address.' . $mailingAddressIndex . '.narrative.' . $narrativeIndex . '.narrative' . '.required'] = 'Mailing Address is required';
                }
            }
            foreach ($contactInfo['telephone'] as $telephoneIndex => $telephone) {
                $rules['contact_info.' . $contactInfoIndex . '.telephone.' . $telephoneIndex . '.telephone' . '.numeric'] = 'Telephone should be numeric';
            }
            foreach ($contactInfo['email'] as $emailIndex => $email) {
                $rules['contact_info.' . $contactInfoIndex . '.email.' . $emailIndex . '.email' . '.email'] = 'Email should be valid email address';
            }
        }

        return $messages;
    }
}
