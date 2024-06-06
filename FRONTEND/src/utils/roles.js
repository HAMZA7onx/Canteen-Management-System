export function hasRole(userRoles, requiredRole) {
    return userRoles.some((role) => role.name === requiredRole);
}
