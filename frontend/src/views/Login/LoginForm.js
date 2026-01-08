import { useState } from "react";
import { parseJwt } from "../../utils/jwtUtils";
import loginIcon from '../../assets/login-icon.png';
import loginIconBtn from '../../assets/login-icon-btn.png';


const LoginForm = ({ onLogin }) => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");

    try {
      const response = await fetch("http://localhost:8080/api/login", {
        method: "POST",
        headers: {
          "accept": "application/json",
          "Content-Type": "application/json"
        },
        body: JSON.stringify({ username: email, password })
      });

      if (!response.ok) {
        let msg = "Nieprawidłowy login lub hasło";
        try {
          const data = await response.json();
          if (data?.message) msg = data.message;
        } catch {}
        setError(msg);
        return;
      }

      const data = await response.json();
      const jwt = data.token ?? data.accessToken;

      if (!jwt) {
        setError("Brak tokena w odpowiedzi serwera");
        return;
      }

      localStorage.setItem("token", jwt);
      const payload = parseJwt(jwt);
      const roles = payload?.roles || [];

      localStorage.setItem("roles", JSON.stringify(roles));

      if (onLogin) onLogin(jwt, roles, payload.username || "");

    } catch (err) {
      console.error("Błąd logowania:", err);
      setError("Wystąpił błąd połączenia z serwerem");
    }
  };

  return (
    <div className="login-card">
      <img src={loginIcon} alt="Login Icon" className="login-icon" />
      <h5 className="title">System Zarządzania Kadrą</h5>
      <p className="subtitle">Zaloguj się do systemu</p>

      <form className="login-form" onSubmit={handleSubmit}>
        <div className="flex flex-col">
          <label>Email</label>
          <input
            type="text"
            placeholder="Email"
            className="input-field"
            required
            value={email}
            onChange={e => setEmail(e.target.value)}
          />
        </div>

        <div className="flex flex-col">
          <label>Hasło</label>
          <input
            type="password"
            placeholder="***********"
            className="input-field"
            required
            value={password}
            onChange={e => setPassword(e.target.value)}
          />
        </div>

        {error && (
          <p className="error-message" style={{ color: "red", margin: "10px 0" }}>
            {error}
          </p>
        )}

        <button type="submit" className="login-button green-btn">
          <img src={loginIconBtn} alt="Login Button Icon" className="button-icon" />
          Zaloguj
        </button>
      </form>
    </div>
  );
};

export default LoginForm;
