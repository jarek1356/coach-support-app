import { parseJwt } from "./jwtUtils";

export const getToken = () => localStorage.getItem("token");

export const getRoles = () => {
  const token = getToken();
  if (!token) return [];

  const payload = parseJwt(token);
  return payload?.roles || [];
};

export const hasRole = (role) => {
  return getRoles().includes(role);
};

export const isLoggedIn = () => {
  return !!getToken();
};

export const getMainRole = () => {
  if (hasRole("ROLE_ADMIN")) return "ADMIN";
  if (hasRole("ROLE_PARENT")) return "PARENT";
  if (hasRole("ROLE_PLAYER")) return "PLAYER";
  return null;
};
