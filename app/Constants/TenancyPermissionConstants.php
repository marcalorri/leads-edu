<?php

namespace App\Constants;

class TenancyPermissionConstants
{
    public const TENANCY_PERMISSION_PREFIX = 'tenancy:';

    public const ROLE_ADMIN = 'admin';

    public const ROLE_USER = 'user';

    public const TENANT_CREATOR_ROLE = self::ROLE_ADMIN;

    public const PERMISSION_CREATE_SUBSCRIPTIONS = 'tenancy: create subscriptions';

    public const PERMISSION_UPDATE_SUBSCRIPTIONS = 'tenancy: update subscriptions';

    public const PERMISSION_DELETE_SUBSCRIPTIONS = 'tenancy: delete subscriptions';

    public const PERMISSION_VIEW_SUBSCRIPTIONS = 'tenancy: view subscriptions';

    public const PERMISSION_CREATE_ORDERS = 'tenancy: create orders';

    public const PERMISSION_UPDATE_ORDERS = 'tenancy: update orders';

    public const PERMISSION_DELETE_ORDERS = 'tenancy: delete orders';

    public const PERMISSION_VIEW_ORDERS = 'tenancy: view orders';

    public const PERMISSION_VIEW_TRANSACTIONS = 'tenancy: view transactions';

    public const PERMISSION_INVITE_MEMBERS = 'tenancy: invite members';

    public const PERMISSION_MANAGE_TEAM = 'tenancy: manage team';

    public const PERMISSION_UPDATE_TENANT_SETTINGS = 'tenancy: update tenant settings';

    public const PERMISSION_VIEW_ROLES = 'tenancy: view roles';

    public const PERMISSION_CREATE_ROLES = 'tenancy: create roles';

    public const PERMISSION_UPDATE_ROLES = 'tenancy: update roles';

    public const PERMISSION_DELETE_ROLES = 'tenancy: delete roles';

    // CRM Permissions
    public const PERMISSION_VIEW_ALL_LEADS = 'tenancy: view all leads';
    public const PERMISSION_CREATE_LEADS = 'tenancy: create leads';
    public const PERMISSION_UPDATE_LEADS = 'tenancy: update leads';
    public const PERMISSION_DELETE_LEADS = 'tenancy: delete leads';
    
    public const PERMISSION_VIEW_ALL_CONTACTS = 'tenancy: view all contacts';
    public const PERMISSION_CREATE_CONTACTS = 'tenancy: create contacts';
    public const PERMISSION_UPDATE_CONTACTS = 'tenancy: update contacts';
    public const PERMISSION_DELETE_CONTACTS = 'tenancy: delete contacts';
    
    public const PERMISSION_VIEW_ALL_EVENTS = 'tenancy: view all events';
    public const PERMISSION_CREATE_EVENTS = 'tenancy: create events';
    public const PERMISSION_UPDATE_EVENTS = 'tenancy: update events';
    public const PERMISSION_DELETE_EVENTS = 'tenancy: delete events';
    
    public const PERMISSION_VIEW_ALL_NOTES = 'tenancy: view all notes';
    public const PERMISSION_CREATE_NOTES = 'tenancy: create notes';
    public const PERMISSION_UPDATE_NOTES = 'tenancy: update notes';
    public const PERMISSION_DELETE_NOTES = 'tenancy: delete notes';
    
    public const PERMISSION_MANAGE_CONFIGURATION = 'tenancy: manage configuration';
    public const PERMISSION_VIEW_DASHBOARD_STATS = 'tenancy: view dashboard stats';
    public const PERMISSION_IMPORT_LEADS = 'tenancy: import leads';
}
