import React from "react";
import { BrowserRouter, Routes, Route, Navigate, useNavigate } from "react-router-dom";
import Login from "./views/Login/Login";
import AdminDashboard from "./views/Dashboard/AdminDashboard";
import PlayerDashboard from "./views/Dashboard/PlayerDashboard";
import ParentDashboard from "./views/Dashboard/ParentDashboard"; 

// Wrapper, żeby użyć useNavigate
function LoginWrapper() {
  const navigate = useNavigate();

  const handleLogin = (token, roles, username) => {
    localStorage.setItem("token", token);
    localStorage.setItem("roles", JSON.stringify(roles));
    localStorage.setItem("username", username);

    // Przekierowanie na podstawie ról
    if (roles.includes("ROLE_ADMIN")) {
      navigate("/admin");
    } else if (roles.includes("ROLE_PARENT")) {
      navigate("/parent");
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
        <Route path="/parent" element={<ParentDashboard />} /> {/* Dodana trasa */}
        <Route path="/player" element={<PlayerDashboard />} />
        <Route path="*" element={<Navigate to="/login" replace />} />
      </Routes>
    </BrowserRouter>
  );
}
