const LoginForm = () => {
  return (
    <div className="login-card">
      <h5 className="title">System Zarządzania Kadrą</h5>
      <p className="subtitle">Zaloguj się do systemu</p>

      <form className="login-form">
        <div className="flex flex-col">
        <label htmlFor="email">Email</label>
        <input
          type="email"
          id="email"
          placeholder="twój.email@klub.pl"
          className="input-field"
          required
        />
        </div>
        <div className="flex flex-col">
        <label htmlFor="password">Hasło</label>
        <input
          type="password"
          id="password"
          placeholder="********"
          className="input-field"
          required
        />
        </div>

        <button type="submit" className="login-button">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
            className="arrow-icon"
          >
            <path
              fillRule="evenodd"
              d="M3 10a.75.75 0 0 1 .75-.75h10.94l-3.22-3.22a.75.75 0 0 1 1.06-1.06l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 0 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z"
              clipRule="evenodd"
            />
          </svg>
          Zaloguj
        </button>
      </form>
    </div>
  );
};

export default LoginForm;
