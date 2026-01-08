import React from "react";
import { BrowserRouter, Routes, Route, Navigate, useNavigate } from "react-router-dom";
import Login from "./views/Login/Login";
import AdminDashboard from "./views/Dashboard/AdminDashboard";
import PlayerDashboard from "./views/Dashboard/PlayerDashboard";

// Wrapper, żeby użyć useNavigate
function LoginWrapper() {
  const navigate = useNavigate();

  const handleLogin = (token, roles, username) => {
    localStorage.setItem("token", token);
    localStorage.setItem("roles", JSON.stringify(roles));
    localStorage.setItem("username", username);

    console.log("Token zapisany w localStorage:", token);
    console.log("Role zapisane w localStorage:", roles);
    console.log("Username zapisany w localStorage:", username);

    // przekierowanie po roli
    if (roles.includes("ROLE_ADMIN")) {
      navigate("/admin");
    } else {
      navigate("/player");
    }
  };

  return <Login onLogin={handleLogin} />;
}

export default function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/login" element={<LoginWrapper />} />
        <Route path="/admin" element={<AdminDashboard />} />
        <Route path="/player" element={<PlayerDashboard />} />
        <Route path="*" element={<Navigate to="/login" replace />} />
      </Routes>
    </BrowserRouter>
  );
}
