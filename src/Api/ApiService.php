<?php

/**
 * This file is part of TeamELF
 *
 * (c) GuessEver <guessever@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeamELF\Api;

use TeamELF\Api\Controller\Attachment\UploadController;
use TeamELF\Api\Controller\Auth\AuthCheckController;
use TeamELF\Api\Controller\Auth\ForgetPasswordController;
use TeamELF\Api\Controller\Auth\LoginController;
use TeamELF\Api\Controller\Auth\LogoutController;
use TeamELF\Api\Controller\Auth\ResetPasswordController;
use TeamELF\Api\Controller\Config\ConfigListController;
use TeamELF\Api\Controller\Config\ConfigUpdateController;
use TeamELF\Api\Controller\Config\LogoUpdateController;
use TeamELF\Api\Controller\Extension\ExtensionActivateController;
use TeamELF\Api\Controller\Extension\ExtensionListController;
use TeamELF\Api\Controller\Extension\ExtensionUninstallController;
use TeamELF\Api\Controller\Helper\PinyinController;
use TeamELF\Api\Controller\Member\MemberCreateController;
use TeamELF\Api\Controller\Member\MemberItemController;
use TeamELF\Api\Controller\Member\MemberListController;
use TeamELF\Api\Controller\Member\MemberRoleUpdateController;
use TeamELF\Api\Controller\Member\MemberUpdateController;
use TeamELF\Api\Controller\Permission\PermissionListController;
use TeamELF\Api\Controller\Permission\PermissionUpdateController;
use TeamELF\Api\Controller\Role\RoleCreateController;
use TeamELF\Api\Controller\Role\RoleDeleteController;
use TeamELF\Api\Controller\Role\RoleListController;
use TeamELF\Api\Controller\Role\RoleUpdateController;
use TeamELF\Application\AbstractService;
use TeamELF\Event\RoutesWillBeLoaded;

class ApiService extends AbstractService
{
    /**
     * register all api services
     */
    public function register()
    {
        app()->listen(RoutesWillBeLoaded::class, [$this, 'handleRoutes']);
    }

    /**
     * handle RoutesWillBeLoaded event
     * all core api routes will be loaded here
     *
     * @param RoutesWillBeLoaded $event
     */
    public function handleRoutes($event)
    {
        $event->getRouter()

            // --------------------
            // | Attachment
            // --------------------
            ->prefix('/api/attachment')
            ->post('attachment-upload', '', UploadController::class)

            // --------------------
            // | Helpers
            // --------------------
            ->prefix('/api/helper')
            ->get('helper-pinyin', '/pinyin', PinyinController::class)

            // --------------------
            // | Permission
            // --------------------
            ->prefix('/api/permission')
            ->get('permission-list', '', PermissionListController::class)
            ->put('permission-update', '', PermissionUpdateController::class)

            // --------------------
            // | Config
            // --------------------
            ->prefix('/api/config')
            ->get('config-list', '', ConfigListController::class)
            ->put('config-update', '/{key}', ConfigUpdateController::class)
            ->post('config-logo', '/logo', LogoUpdateController::class)

            // --------------------
            // | Auth
            // --------------------
            ->prefix('/api/auth')
            ->get('auth-check', '', AuthCheckController::class)
            ->post('auth-login', '/login', LoginController::class)
            ->post('auth-logout', '/logout', LogoutController::class)
            ->post('auth-forget', '/forget', ForgetPasswordController::class)
            ->post('auth-reset', '/reset/{token}', ResetPasswordController::class)

            // --------------------
            // | Member
            // --------------------
            ->prefix('/api/member')
            ->get('member-list', '', MemberListController::class)
            ->post('member-create', '', MemberCreateController::class)
            ->get('member-item', '/{username}', MemberItemController::class)
            ->put('member-update', '/{username}', MemberUpdateController::class)
            ->put('member-role-update', '/{username}/role', MemberRoleUpdateController::class)

            // --------------------
            // | Role
            // --------------------
            ->prefix('/api/role')
            ->get('role-list', '', RoleListController::class)
            ->post('role-create', '', RoleCreateController::class)
            ->put('role-update', '/{id}', RoleUpdateController::class)
            ->delete('role-delete', '/{id}', RoleDeleteController::class)

            // --------------------
            // | Extension
            // --------------------
            ->prefix('/api/extension')
            ->get('extension-list', '', ExtensionListController::class)
            ->put('extension-activate', '/{vendor}/{package}', ExtensionActivateController::class)
            ->delete('extension-uninstall', '/{vendor}/{package}', ExtensionUninstallController::class)

            // set prefix back to empty
            ->prefix('');
    }
}
