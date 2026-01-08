// Login.js
import React from "react";
import LoginForm from "./LoginForm";

export default function Login({ onLogin }) {
  return (
    <div className="login-bg">
      <LoginForm onLogin={onLogin} />
    </div>
  );
}
