<?php

class Privileges {

    private bool $companyAccess;
    private bool $customerAccess;
    private bool $transporterAccess;

    public function __construct(bool $companyAccess, bool $customerAccess, bool $transporterAccess) {
        $this->companyAccess = $companyAccess;
        $this->customerAccess = $customerAccess;
        $this->transporterAccess = $transporterAccess;
    }

    function hasCompanyAccess () : bool {
        return $this->companyAccess;
    }

    function hasCustomerAccess () : bool {
        return $this->customerAccess;
    }

    function hasTransporterAccess () : bool {
        return $this->transporterAccess;
    }

    function hasPrivileges (Privileges $requiredPrivileges) : bool {
        if ($requiredPrivileges->hasCompanyAccess() && !$this->hasCompanyAccess()) {
            return false;
        }

        if ($requiredPrivileges->hasCustomerAccess() && !$this->hasCustomerAccess()) {
            return false;
        }

        if ($requiredPrivileges->hasTransporterAccess() && !$this->hasTransporterAccess()) {
            return false;
        }

        return true;
    }

    function hasNoPrivileges () : bool {
        return !$this->hasCompanyAccess() && !$this->hasCustomerAccess() && !$this->hasTransporterAccess();
    }

}