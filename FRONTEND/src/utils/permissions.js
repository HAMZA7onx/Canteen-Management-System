
export function hasPermission(userPermissions, requiredPermission) {
    return userPermissions.includes(requiredPermission);
}
