import React, { Suspense, lazy } from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import Layout from './components/Layout';
import PrivateRoute from './components/PrivateRoute';
import { AuthProvider } from './contexts/AuthContext'; 
import { Toaster } from 'react-hot-toast';

// Import bootstrap.js
import './bootstrap';

import '../css/app.css';

// Lazy load components
const DanhSachVatPham = lazy(() => import('./components/KyTranCac/DanhSachVatPham'));
const TrangChu = lazy(() => import('./components/TrangChu/TrangChu'));
const BlogNews = lazy(() => import('./components/Blog/BlogNews'));
const BlogList = lazy(() => import('./components/Blog/BlogList'));
const UserProfile = lazy(() => import('./components/UserInfo/UserProfile'));
const DailyGift = lazy(() => import('./components/UserInfo/DailyGift'));
import ActivateAccount from './components/ActivateAccount';

const App = () => (
  <AuthProvider> 
    <Router basename="/"> 
      <Layout>
        <Toaster 
          position="top-right"
          toastOptions={{
            duration: 4000,
            style: {
              background: '#333',
              color: '#fff',
            },
          }}
        />
        <Suspense fallback={<div>Loading...</div>}>
          <Routes>
            <Route path="/" element={<TrangChu />} />
            <Route path="/tin-tuc" element={<BlogList />} />
            <Route path="/tin-tuc/:slug" element={<BlogNews />} />
            <Route path="/activate-account/:token" element={<ActivateAccount />} />
            <Route 
              path="/ky-tran-cac" 
              element={
                <PrivateRoute>
                  <DanhSachVatPham />
                </PrivateRoute>
              } 
            />
            <Route 
              path="/thong-tin-tai-khoan" 
              element={
                <PrivateRoute>
                  <UserProfile />
                </PrivateRoute>
              } 
            />
             <Route 
              path="/daily-gift" 
              element={
                <PrivateRoute>
                  <DailyGift />
                </PrivateRoute>
              }
            />
          </Routes>
        </Suspense>
      </Layout>
    </Router>
  </AuthProvider>  
);

const container = document.getElementById('app');
if (container) {
  const root = createRoot(container);
  root.render(
    <React.StrictMode>
      <App />
    </React.StrictMode>
  );
}