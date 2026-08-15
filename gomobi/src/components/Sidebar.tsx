import { NavLink } from 'react-router-dom'
import { LayoutDashboard, TrainFront, Radio, Settings } from 'lucide-react'
import Logo from './Logo'

const navItems = [
  { to: '/dashboard', label: 'Dashboard', icon: LayoutDashboard },
  { to: '/trains', label: 'Train Monitoring', icon: TrainFront },
  { to: '/sensors', label: 'Sensors', icon: Radio },
  { to: '/settings', label: 'Settings', icon: Settings },
]

export default function Sidebar() {
  return (
    <aside className="hidden md:flex h-screen w-[200px] shrink-0 flex-col bg-gomobi-dark px-3 py-5">
      <div className="mb-8 px-2">
        <Logo variant="light" size="sm" />
      </div>
      <nav className="flex flex-col gap-1">
        {navItems.map(({ to, label, icon: Icon }) => (
          <NavLink
            key={to}
            to={to}
            className={({ isActive }) =>
              `flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-body transition-colors ${
                isActive
                  ? 'bg-gomobi-purple text-white font-semibold'
                  : 'text-gray-300 hover:bg-white/5 hover:text-white'
              }`
            }
          >
            <Icon size={16} />
            {label}
          </NavLink>
        ))}
      </nav>
    </aside>
  )
}
