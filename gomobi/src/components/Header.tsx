import { Menu } from 'lucide-react'
import Logo from './Logo'
import UserDropdown from './UserDropdown'

interface HeaderProps {
  onMenuClick: () => void
}

export default function Header({ onMenuClick }: HeaderProps) {
  return (
    <header className="flex h-16 shrink-0 items-center justify-between border-b border-gray-100 bg-white px-4 md:px-6">
      <div className="flex items-center gap-3">
        <button
          onClick={onMenuClick}
          className="rounded-md p-1.5 text-gomobi-dark hover:bg-gray-100 md:hidden"
          aria-label="Open menu"
        >
          <Menu size={20} />
        </button>
        <div className="md:hidden">
          <Logo size="sm" />
        </div>
      </div>
      <UserDropdown />
    </header>
  )
}
