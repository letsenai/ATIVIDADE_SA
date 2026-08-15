import { NavLink } from 'react-router-dom'
import { LayoutDashboard, TrainFront, Radio, Settings, X } from 'lucide-react'

const navItems = [
  { to: '/dashboard', label: 'Dashboard', icon: LayoutDashboard },
  { to: '/trains', label: 'Train Monitoring', icon: TrainFront },
  { to: '/sensors', label: 'Sensors', icon: Radio },
  { to: '/settings', label: 'Settings', icon: Settings },
]

interface MobileMenuProps {
  open: boolean
  onClose: () => void
}

export default function MobileMenu({ open, onClose }: MobileMenuProps) {
  if (!open) return null
  return (
    <div className="fixed inset-0 z-40 md:hidden">
      <div className="absolute inset-0 bg-black/30" onClick={onClose} />
      <div className="absolute left-0 top-0 h-full w-[78%] max-w-[280px] bg-gomobi-dark px-3 py-5">
        <div className="mb-4 flex items-center justify-end px-2">
          <button
            onClick={onClose}
            className="rounded-md p-1 text-gray-300 hover:bg-white/10 hover:text-white"
            aria-label="Close menu"
          >
            <X size={18} />
          </button>
        </div>
        <nav className="flex flex-col gap-1">
          {navItems.map(({ to, label, icon: Icon }) => (
            <NavLink
              key={to}
              to={to}
              onClick={onClose}
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
      </div>
    </div>
  )
}
