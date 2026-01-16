import React from 'react';
import { Outlet } from 'react-router-dom';
import Menu from './Menu/Menu';
import { getMainRole } from "../utils/auth";

export default function MainLayout() {
  const mainRole = getMainRole();

  return (
    <div className="dashboard-layout flex">
      <Menu role={mainRole} />
      <div className="dashboard-content flex-1">
        <Outlet /> 
      </div>
    </div>
  );
}