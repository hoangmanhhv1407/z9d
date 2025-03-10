// contexts/AdminAuthContext.jsx
export const AdminAuthProvider = ({ children }) => {
    const [adminUser, setAdminUser] = useState(null);
    const [isAdminAuthenticated, setIsAdminAuthenticated] = useState(false);
  
    const loginAdmin = useCallback((token, userData) => {
      if (token && userData) {
        localStorage.setItem('admin_token', token);
        setAdminUser(userData);
        setIsAdminAuthenticated(true);
      }
    }, []);
  
    const logoutAdmin = useCallback(() => {
      localStorage.removeItem('admin_token');
      setAdminUser(null);
      setIsAdminAuthenticated(false);
    }, []);
  
    // ... rest of the context logic
  
    return (
      <AdminAuthContext.Provider value={{
        adminUser,
        isAdminAuthenticated,
        loginAdmin,
        logoutAdmin
      }}>
        {children}
      </AdminAuthContext.Provider>
    );
  };