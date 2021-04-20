<?php

/**
 * Class Privileges
 * Describing privileges for the various endpoints
 */
class Privileges {

    private bool $companyAccess;
    private bool $customerAccess;
    private bool $transporterAccess;

    /**
     * Privileges constructor.
     * @param bool $companyAccess access to the company endpoint
     * @param bool $customerAccess access to the customer endpoint
     * @param bool $transporterAccess access to the transporter endpoint
     */
    public function __construct(bool $companyAccess, bool $customerAccess, bool $transporterAccess) {
        $this->companyAccess = $companyAccess;
        $this->customerAccess = $customerAccess;
        $this->transporterAccess = $transporterAccess;
    }

    /**
     * @return bool true if has access to company endpoint
     */
    function hasCompanyAccess () : bool {
        return $this->companyAccess;
    }

    /**
     * @return bool true if has access to customer endpoint
     */
    function hasCustomerAccess () : bool {
        return $this->customerAccess;
    }

    /**
     * @return bool true if has access to transporter endpoint
     */
    function hasTransporterAccess () : bool {
        return $this->transporterAccess;
    }

    /**
     * @return bool true if has access to all the required privileges
     */
    function hasAccess (Privileges $requiredPrivileges) : bool {
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

    /**
     * @return bool true if no privileges are set for this object
     */
    function hasNoPrivileges () : bool {
        return !$this->hasCompanyAccess() && !$this->hasCustomerAccess() && !$this->hasTransporterAccess();
    }

}