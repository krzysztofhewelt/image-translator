export interface LoginFormErrors {
  errors: {
    account: Array<string>;
    email: Array<string>;
    password: Array<string>;
  };
}

export interface RegisterFormErrors {
  errors: {
    username: Array<string>;
    email: Array<string>;
    password: Array<string>;
  };
}

export interface ChangePasswordFormErrors {
  errors: {
    current_password: Array<string>;
    new_password: Array<string>;
  }
}
