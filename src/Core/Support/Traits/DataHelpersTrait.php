<?php declare(strict_types=1);

namespace Simtabi\Pheg\Core\Support\Traits;

trait DataHelpersTrait
{

    public function getUserGroups($default = null)
    {
        return $this->setKey('user_groups')->setDefault($default)->getData();
    }

    public function getAccessGroups($default = null)
    {
        return $this->setKey('access_groups')->setDefault($default)->getData();
    }

    public function getSecurityStatuses($default = null)
    {
        return $this->setKey('security_statuses')->setDefault($default)->getData();
    }

    public function getAgeLimits($default = null)
    {
        return $this->setKey('age_limits')->setDefault($default)->getData();
    }

    public function getTriggerFrequencies($default = null)
    {
        return $this->setKey('trigger_frequencies')->setDefault($default)->getData();
    }

    public function getTimeOptions($default = null)
    {
        return $this->setKey('time_options')->setDefault($default)->getData();
    }

    public function getCalendarOptions($default = null)
    {
        return $this->setKey('calendar_options')->setDefault($default)->getData();
    }

    public function getDatetimeFormats($default = null)
    {
        return $this->setKey('datetime_formats')->setDefault($default)->getData();
    }

    public function getLanguageOptions($default = null)
    {
        return $this->setKey('language_options')->setDefault($default)->getData();
    }

    public function getLinkTargeOptions($default = null)
    {
        return $this->setKey('link_target_options')->setDefault($default)->getData();
    }

    public function getPriorityTypes($default = null)
    {
        return $this->setKey('priority_types')->setDefault($default)->getData();
    }

    public function getContentStatuses($default = null)
    {
        return $this->setKey('content_statuses')->setDefault($default)->getData();
    }

    public function getProgressStatusTypes($default = null)
    {
        return $this->setKey('progress_status_types')->setDefault($default)->getData();
    }

    public function getAspectRatios($default = null)
    {
        return $this->setKey('aspect_ratios')->setDefault($default)->getData();
    }

    public function getAvatarTypes($default = null)
    {
        return $this->setKey('avatar_types')->setDefault($default)->getData();
    }

    public function getUploadMethods($default = null)
    {
        return $this->setKey('upload_methods')->setDefault($default)->getData();
    }

    public function getGendersTypes($default = null)
    {
        return $this->setKey('genders')->setDefault($default)->getData();
    }

    public function getAvailabilityTypes($default = null)
    {
        return $this->setKey('availability_types')->setDefault($default)->getData();
    }

    public function getEmploymentStatusTypes($default = null)
    {
        return $this->setKey('employment_status_types')->setDefault($default)->getData();
    }

    public function getSocialMediaProviders($default = null)
    {
        return $this->setKey('socialmedia_providers')->setDefault($default)->getData();
    }

    public function getSalutations($default = null)
    {
        return $this->setKey('salutations')->setDefault($default)->getData();
    }

    public function getMediaTypes($default = null)
    {
        return $this->setKey('media_types')->setDefault($default)->getData();
    }

    public function getArticleFormatsTypes($default = null)
    {
        return $this->setKey('article_formats')->setDefault($default)->getData();
    }

    public function getArticleTypes($default = null)
    {
        return $this->setKey('article_types')->setDefault($default)->getData();
    }

    public function getMailingProtocols($default = null)
    {
        return $this->setKey('mailing_protocols')->setDefault($default)->getData();
    }

    public function getAuthOptions($default = null)
    {
        return $this->setKey('auth_options')->setDefault($default)->getData();
    }

    public function getProfessionTypes($default = null)
    {
        return $this->setKey('profession_types')->setDefault($default)->getData();
    }

    public function getCompanyRegistrationTypes($default = null)
    {
        return $this->setKey('company_registration_types')->setDefault($default)->getData();
    }

    public function getOccupationTypes($default = null)
    {
        return $this->setKey('occupation_types')->setDefault($default)->getData();
    }

    public function getIndustryTypes($default = null)
    {
        return $this->setKey('industry_types')->setDefault($default)->getData();
    }

    public function getCareerTypes($default = null)
    {
        return $this->setKey('career_types')->setDefault($default)->getData();
    }

    public function getCopyrightTexts($default = null)
    {
        return $this->setKey('copyright_texts')->setDefault($default)->getData();
    }

    public function getMenuLocations($default = null)
    {
        return $this->setKey('menu_locations')->setDefault($default)->getData();
    }

    public function getLinkTypes($default = null)
    {
        return $this->setKey('link_types')->setDefault($default)->getData();
    }

    public function getAnchorTypes($default = null)
    {
        return $this->setKey('anchor_types')->setDefault($default)->getData();
    }

    public function getLinkTo($default = null)
    {
        return $this->setKey('link_to')->setDefault($default)->getData();
    }

    public function getSearchOptions($default = null)
    {
        return $this->setKey('search_options')->setDefault($default)->getData();
    }

    public function getAddressContactTypes($default = null)
    {
        return $this->setKey('address_contact_types')->setDefault($default)->getData();
    }

    public function getHelpdeskOptions($default = null)
    {
        return $this->setKey('helpdesk_options')->setDefault($default)->getData();
    }
    
    
}