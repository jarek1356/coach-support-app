import React from 'react';
import { getMainRole } from "../../utils/auth";

export default function Dashboard() {
  const mainRole = getMainRole();

  // Zwracamy tylko treść środkową. 
  // Menu i div "dashboard-layout" są już w MainLayout.
  return (
    <>
      {mainRole === "ADMIN" && (
        <h1>Dashboard Trenera</h1>
      )}
      {mainRole === "PARENT" && (
        <h1>Dashboard rodzica</h1>
      )}
      {mainRole === "PLAYER" && (
        <h1>Dashboard zawodnika</h1>
      )}
      
    </>
  );
}