import store from '~/store'

/**
 * This is middleware to check the current user permission.
 *
 * middleware: 'role:admin,manager',
 */

export default (to, from, next, permissions) => {

  // Grab the user
  const user = store.getters['auth/user']

  permissions = permissions.split(',')

  // Check if the user has all of the required permissions...
  if (!permissions.every(p => user.permissions.includes(p))) {
    next('/')
  }

  next()
}
