import React, { useState, useEffect } from 'react';
import loginIcon from '../../assets/login-icon.png';
import { NavLink, useNavigate } from 'react-router-dom';
import { 
  DashboardIcon, 
  CalendarIcon, 
  AttendanceIcon, 
  StatsIcon, 
  TestsIcon, 
  UsersIcon, 
  SettingsIcon,
  LogoutIcon 
} from './IconsMenu';

function Menu({ role }) {
  const [userData, setUserData] = useState(null);
  const navigate = useNavigate();

  const roleDisplayNames = {
    "ADMIN": "Trener",
    "PARENT": "Rodzic",
    "PLAYER": "Zawodnik"
  };

  useEffect(() => {
    const fetchUserData = async () => {
      const cachedData = sessionStorage.getItem('userData');
      if (cachedData) {
        setUserData(JSON.parse(cachedData));
        return; 
      }

      try {
        const token = localStorage.getItem('token');
        if (!token) return;

        const response = await fetch('http://localhost:8080/api/info', {
          method: 'GET',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
          }
        });

        if (response.ok) {
          const data = await response.json();
          setUserData(data);
          sessionStorage.setItem('userData', JSON.stringify(data));
        }
      } catch (error) {
        console.error("Błąd pobierania danych w Menu:", error);
      }
    };

    fetchUserData();
  }, []); 

  const handleLogout = () => {
    localStorage.removeItem('token');
    sessionStorage.removeItem('userData');
    navigate('/login');
  };

  const firstName = userData?.firstName || "";
  const lastName = userData?.lastName || "";
  const email = userData?.email || "";

  return (
    <div id='menu'>
      <div className="menu-user">
        <div className='flex logo-dashboard-box'>
          <img src={loginIcon} alt="Logo" className="Icon" />
          <span className='flex flex-col justify-between'>
            <h2>TrainPro</h2>
            <p>{roleDisplayNames[role] || role}</p>
          </span>
        </div>
        
        <div className='user-info flex items-center'>
          <div className='initials'>
            {firstName && lastName 
              ? `${firstName.charAt(0).toUpperCase()}${lastName.charAt(0).toUpperCase()}`
              : "??"}
          </div>
          <span className='user-info-details'>
            {userData ? (
              <>
                <div className="user-full-name">{`${firstName} ${lastName}`}</div>
                <div className="user-email">{email}</div>
              </>
            ) : (
              <div>Ładowanie...</div>
            )}
          </span>
        </div>
      </div>

      <nav className="menu-nav">
        <NavLink to="/dashboard" end className="nav-item">
          <div className="nav-icon"><DashboardIcon /></div>
          <span>Pulpit</span>
        </NavLink>

        <NavLink to="/users" className="nav-item">
          <div className="nav-icon"><UsersIcon /></div>
          <span>Użytkownicy</span>
        </NavLink>

        <NavLink to="/calendar" className="nav-item">
          <div className="nav-icon"><CalendarIcon /></div>
          <span>Kalendarz</span>
        </NavLink>

        <NavLink to="/attendance" className="nav-item">
          <div className="nav-icon"><AttendanceIcon /></div>
          <span>Frekwencja</span>
        </NavLink>

        <NavLink to="/tests" className="nav-item">
          <div className="nav-icon"><TestsIcon /></div>
          <span>Testy sprawnościowe</span>
        </NavLink>

        <NavLink to="/stats" className="nav-item">
          <div className="nav-icon"><StatsIcon /></div>
          <span>Statystyki</span>
        </NavLink>

        <NavLink to="/settings" className="nav-item">
          <div className="nav-icon"><SettingsIcon /></div>
          <span>Ustawienia</span>
        </NavLink>
      </nav>

      <div className='logout'>
        <button onClick={handleLogout} className="logout-button">
          <div className="nav-icon">
            <LogoutIcon />
          </div>
          <span>Wyloguj</span>
        </button>
      </div>
    </div>
  );
}

export default Menu;