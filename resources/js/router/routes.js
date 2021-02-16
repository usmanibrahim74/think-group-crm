function page (path) {
  return () => import(/* webpackChunkName: '' */ `~/pages/${path}`).then(m => m.default || m)
}

export default [
  { path: '/', name: 'dashboard', component: page('dashboard.vue'), meta:{ gate: 'access-dashboard' } },

  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/register', name: 'register', component: page('auth/register.vue') },
  { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
  { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
  { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
  { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },

  { path: '/home', name: 'home', component: page('dashboard.vue') },
  {
    path: '/settings',
    component: page('settings/index.vue'),
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue') },
      { path: 'password', name: 'settings.password', component: page('settings/password.vue') }
    ]
  },

  { path: '/roles', name: 'roles', component: page('roles/index.vue'), meta:{ gate: 'view-role' } },
  { path: '/roles/add', name: 'roles.add', component: page('roles/create.vue') },
  { path: '/roles/:id', name: 'roles.edit', component: page('roles/edit.vue') },
  { path: '/roles/:id/permissions', name: 'roles.permissions', component: page('roles/permissions.vue') },

  { path: '/permissions', name: 'permissions', component: page('permissions/index.vue') },
  { path: '/permissions/add', name: 'permissions.add', component: page('permissions/create.vue') },
  { path: '/permissions/:id', name: 'permissions.edit', component: page('permissions/edit.vue') },

  { path: '/industries', name: 'industries', component: page('candidates/industries.vue'), meta:{ gate: 'view-industry' } },


  { path: '/candidates', name: 'candidates', component: page('candidates/index.vue') },
  { path: '/candidates/shortlisted', name: 'candidates.shortlisted', component: page('candidates/shortlisted.vue') },
  { path: '/candidates/add', name: 'candidates.add', component: page('candidates/create.vue') },
  { path: '/candidates/:id', name: 'candidates.view', component: page('candidates/view.vue') },
  { path: '/candidates/:id/edit', name: 'candidates.edit', component: page('candidates/edit.vue') },

  { path: '/employers/profiles', name: 'employers.profiles', component: page('employers/profiles/index.vue') },
  { path: '/employers/profiles/add', name: 'employers.profiles.add', component: page('employers/profiles/create.vue') },
  { path: '/employers/profiles/:id', name: 'employers.profiles.edit', component: page('employers/profiles/edit.vue') },
  { path: '/employers/profiles/:id/candidates', name: 'employers.profiles.candidates', component: page('employers/profiles/candidates.vue') },


  { path: '/employers/accounts', name: 'employers.accounts', component: page('employers/accounts/index.vue') },
  { path: '/employers/accounts/add', name: 'employers.accounts.add', component: page('employers/accounts/create.vue') },
  { path: '/employers/accounts/:id', name: 'employers.accounts.edit', component: page('employers/accounts/edit.vue') },


  { path: '*', component: page('errors/404.vue') }
]
