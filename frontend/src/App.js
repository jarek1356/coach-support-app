import React, { useState, useEffect, useCallback } from 'react';
// Do ikon musimy dodać odpowiednie importy w zależności od wersji CRA
// Jeśli CRA nie ma domyślnie Lucide-react, będziemy musieli je doinstalować
import { Briefcase, Calendar, Users, Home, LogOut } from 'lucide-react';

// === Wymagane Zależności ===
// UWAGA: Create React App nie używa Tailwind CSS domyślnie.
// W tym kodzie ZAKŁADAM, że masz zainstalowane i skonfigurowane
// pakiety Tailwind CSS/PostCSS/Autoprefixer, aby stylizacja działała.
// Jeśli nie masz Tailwind, aplikacja będzie działać, ale bez ładnych stylów!

// === Configuration ===
const __app_id = typeof window.__app_id !== 'undefined' ? window.__app_id : 'default-app-id';
const BACKEND_URL = `/api/v1/apps/${__app_id}/backend`;

const Navigation = ({ active, setActive, userId, onLogout }) => (
    <nav className="p-4 bg-gray-900 text-white shadow-lg fixed bottom-0 md:top-0 md:left-0 md:h-full w-full md:w-64 z-50 rounded-t-xl md:rounded-none">
        <div className="hidden md:block mb-8">
            <h1 className="text-2xl font-bold text-indigo-400 flex items-center">
                <Briefcase className="mr-2" /> Support Coach
            </h1>
            <p className="text-xs text-gray-500 mt-1 truncate">User ID: {userId}</p>
        </div>
        <ul className="flex justify-around md:flex-col md:space-y-2">
            <NavItem
                icon={<Home className="w-5 h-5" />}
                label="Dashboard"
                name="dashboard"
                active={active}
                onClick={setActive}
            />
            <NavItem
                icon={<Users className="w-5 h-5" />}
                label="Kadry"
                name="users"
                active={active}
                onClick={setActive}
            />
            <NavItem
                icon={<Calendar className="w-5 h-5" />}
                label="Kalendarz"
                name="calendar"
                active={active}
                onClick={setActive}
            />
            <li className="flex-1 md:w-full md:mt-4">
                <button
                    onClick={onLogout}
                    className="w-full flex items-center justify-center md:justify-start px-3 py-2 text-sm font-medium text-red-400 hover:bg-red-900 rounded-lg transition duration-150"
                >
                    <LogOut className="w-5 h-5 mr-0 md:mr-3" />
                    <span className="hidden md:inline">Wyloguj</span>
                </button>
            </li>
        </ul>
    </nav>
);

const NavItem = ({ icon, label, name, active, onClick }) => {
    const isActive = active === name;
    return (
        <li className="flex-1 md:w-full">
            <button
                onClick={() => onClick(name)}
                className={`w-full flex items-center justify-center md:justify-start px-3 py-2 text-sm font-medium rounded-lg transition duration-150 ${
                    isActive
                        ? 'bg-indigo-700 text-white shadow-md'
                        : 'text-gray-400 hover:bg-gray-700 hover:text-white'
                }`}
            >
                {icon}
                <span className="ml-3 hidden md:inline">{label}</span>
            </button>
        </li>
    );
};

// --- Moduł Kadry: Główny Widok Zarządzania Użytkownikami ---
const UsersModule = () => {
    const [users, setUsers] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    // Endpoint API dla użytkowników
    const API_ENDPOINT = `${BACKEND_URL}/users`;

    const fetchUsers = useCallback(async () => {
        setLoading(true);
        setError(null);
        try {
            // TUTAJ NASTĘPUJE ODWOŁANIE DO BACKENDU
            const response = await fetch(API_ENDPOINT);

            if (!response.ok) {
                // Rzucamy błąd, jeśli status nie jest 2xx
                throw new Error(`HTTP error! status: ${response.status} (${response.statusText})`);
            }
            const data = await response.json();
            setUsers(data);

        } catch (e) {
            console.error("Error fetching users:", e);
            setError(`Błąd ładowania danych użytkowników. Sprawdź backend. Szczegóły: ${e.message}`);
        } finally {
            setLoading(false);
        }
    }, [API_ENDPOINT]);

    useEffect(() => {
        fetchUsers();
    }, [fetchUsers]);

    return (
        <div className="p-4 md:p-8">
            <h2 className="text-3xl font-extrabold text-gray-800 mb-6 border-b pb-2">Zarządzanie Kadrą</h2>

            <div className="flex justify-between items-center mb-6">
                <p className="text-sm text-gray-500">Łącznie użytkowników: {users.length}</p>
                <button
                    onClick={fetchUsers} // Użyjemy tego do odświeżania na razie
                    className="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-indigo-700 transition duration-150"
                >
                    Odśwież Listę
                </button>
            </div>

            {loading && (
                <div className="flex justify-center items-center h-48">
                    <div className="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-500"></div>
                    <p className="ml-3 text-indigo-600">Ładowanie danych...</p>
                </div>
            )}

            {error && <div className="p-4 bg-red-100 text-red-700 rounded-lg">{error}</div>}

            {!loading && !error && (
                <div className="space-y-4">
                    {users.map(user => (
                        <UserCard key={user.id} user={user} />
                    ))}
                    {users.length === 0 && <p className="text-center text-gray-500 p-10 bg-gray-50 rounded-lg">Brak użytkowników do wyświetlenia. Upewnij się, że backend jest uruchomiony i zwrócił dane.</p>}
                </div>
            )}
        </div>
    );
};

// Karta wyświetlająca podstawowe informacje o użytkowniku
const UserCard = ({ user }) => {
    // Upewnij się, że role i team są dostępne w przypadku serializacji
    const roleName = user.role?.roleName || 'Brak Roli';
    const teamName = user.team?.teamName || 'Brak Zespołu';

    return (
        <div className="bg-white p-4 sm:p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div className="flex-grow">
                <p className="text-lg font-semibold text-gray-900 truncate">
                    {user.firstName} {user.lastName}
                    {user.isPlayer && <span className="ml-2 px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded-full">Zaw.</span>}
                </p>
                <p className="text-sm text-indigo-600 font-medium mt-1">{roleName}</p>
                <p className="text-xs text-gray-500 mt-0.5">Zespół: {teamName}</p>
            </div>
            <div className="mt-3 sm:mt-0 flex space-x-2">
                <button
                    onClick={() => console.log('Edytuj', user.id)}
                    className="px-3 py-1 text-sm text-indigo-600 border border-indigo-600 rounded-lg hover:bg-indigo-50 transition duration-150"
                >
                    Edytuj
                </button>
                <button
                    onClick={() => console.log('Usuń', user.id)}
                    className="px-3 py-1 text-sm text-red-600 border border-red-600 rounded-lg hover:bg-red-50 transition duration-150"
                >
                    Usuń
                </button>
            </div>
        </div>
    );
};

// --- Komponenty Stron Placeholdery ---
const Dashboard = () => <div className="p-8"><h2 className="text-3xl font-extrabold text-gray-800">Panel Główny</h2><p className="text-gray-600 mt-2">Witaj w systemie Support Coach! Tutaj będą statystyki i skróty.</p></div>;
const CalendarModule = () => <div className="p-8"><h2 className="text-3xl font-extrabold text-gray-800">Kalendarz</h2><p className="text-gray-600 mt-2">Widok kalendarza i planowania wydarzeń.</p></div>;


// === Główny Komponent Aplikacji ===
export default function App() {
    const [activePage, setActivePage] = useState('users');
    const [userId, setUserId] = useState('...'); // Zastąpimy logiką autoryzacji

    // Placeholder dla autoryzacji
    useEffect(() => {
        // Symulacja pobrania ID użytkownika po zalogowaniu
        setTimeout(() => {
            const simulatedId = Math.random().toString(36).substring(2, 10);
            setUserId(simulatedId);
        }, 500);
    }, []);

    const handleLogout = () => {
        console.log("Wylogowanie symulowane.");
        alert("Wylogowano pomyślnie. W przyszłej wersji będzie tutaj pełna obsługa autoryzacji.");
        setUserId('...');
        setActivePage('dashboard');
    };

    const renderContent = () => {
        switch (activePage) {
            case 'dashboard':
                return <Dashboard />;
            case 'users':
                return <UsersModule />;
            case 'calendar':
                return <CalendarModule />;
            default:
                return <Dashboard />;
        }
    };

    // Responsive layout: 64px padding-left on medium screens and up
    return (
        <div className="min-h-screen bg-gray-50 font-inter pb-20 md:pb-0">
            <Navigation
                active={activePage}
                setActive={setActivePage}
                userId={userId}
                onLogout={handleLogout}
            />
            <main className="md:ml-64 pt-4 md:pt-8 min-h-screen">
                <div className="max-w-7xl mx-auto">
                    {renderContent()}
                </div>
            </main>
        </div>
    );
}