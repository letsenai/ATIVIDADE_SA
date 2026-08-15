import { useState } from 'react'
import { Outlet } from 'react-router-dom'
import Sidebar from './Sidebar'
import Header from './Header'
import MobileMenu from './MobileMenu'

export default function Layout() {
  const [menuOpen, setMenuOpen] = useState(false)

  return (
    <div className="flex h-screen w-full overflow-hidden bg-gray-50/60">
      <Sidebar />
      <MobileMenu open={menuOpen} onClose={() => setMenuOpen(false)} />
      <div className="flex min-w-0 flex-1 flex-col">
        <Header onMenuClick={() => setMenuOpen(true)} />
        <main className="flex-1 overflow-y-auto px-4 py-5 md:px-8 md:py-7">
          <Outlet />
        </main>
      </div>
    </div>
  )
}
