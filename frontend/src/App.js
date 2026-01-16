import React from "react";
import { BrowserRouter, Routes, Route, Navigate, useNavigate } from "react-router-dom";

import Login from "./views/Login/Login";
import Dashboard from "./views/Dashboard/Dashboard";
import Calendar from "./views/Calendar/Calendar";
import Attendance from "./views/Attendance/Attendance";
import Settings from "./views/Settings/Settings";
import Stats from "./views/Stats/Stats";
import Tests from "./views/Tests/Tests";
import Users from "./views/Users/Users";

import ProtectedRoute from "./utils/ProtectedRoute";
import MainLayout from "./components/MainLayout"; // Importujemy nowy Layout

function LoginWrapper() {
  const navigate = useNavigate();
  const handleLogin = (token) => {
    localStorage.setItem("token", token);
    navigate("/dashboard");
  };
  return <Login onLogin={handleLogin} />;
}

export default function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/login" element={<LoginWrapper />} />

        <Route element={<ProtectedRoute><MainLayout /></ProtectedRoute>}>
          <Route path="/dashboard" element={<Dashboard />} />
          <Route path="/calendar" element={<Calendar />} />
          <Route path="/attendance" element={<Attendance />} />
          <Route path="/stats" element={<Stats />} />
          <Route path="/tests" element={<Tests />} />
          <Route path="/users" element={<Users />} />
          <Route path="/settings" element={<Settings />} />
        </Route>

        <Route path="*" element={<Navigate to="/login" replace />} />
      </Routes>
    </BrowserRouter>
  );
}