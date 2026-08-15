import { useState, useRef, useEffect } from 'react'
import { User as UserIcon, LogOut } from 'lucide-react'
import { useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import StatusBadge from './StatusBadge'

export default function UserDropdown() {
  const [open, setOpen] = useState(false)
  const ref = useRef<HTMLDivElement>(null)
  const { user, logout } = useAuth()
  const navigate = useNavigate()

  useEffect(() => {
    function handleClick(e: MouseEvent) {
      if (ref.current && !ref.current.contains(e.target as Node)) {
        setOpen(false)
      }
    }
    document.addEventListener('mousedown', handleClick)
    return () => document.removeEventListener('mousedown', handleClick)
  }, [])

  const handleLogout = () => {
    logout()
    setOpen(false)
    navigate('/login')
  }

  return (
    <div ref={ref} className="relative">
      <button
        onClick={() => setOpen((o) => !o)}
        className="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors"
        aria-label="User menu"
      >
        <UserIcon size={16} />
      </button>
      {open && (
        <div className="absolute right-0 top-full z-30 mt-2 w-56 rounded-xl border border-gray-100 bg-white p-3 shadow-dropdown">
          <p className="text-xs font-semibold text-gomobi-dark">User</p>
          <p className="mt-0.5 truncate text-xs text-gray-500">{user?.email}</p>
          <div className="mt-2">
            <StatusBadge status={user?.role ?? 'viewer'} showDot={false} />
          </div>
          <div className="my-3 h-px bg-gray-100" />
          <button
            onClick={handleLogout}
            className="flex w-full items-center gap-2 rounded-lg px-1 py-1.5 text-sm font-body text-red-500 hover:bg-red-50 rounded-md"
          >
            <LogOut size={15} />
            Logout
          </button>
        </div>
      )}
    </div>
  )
}
