import { useState, useRef, useEffect } from 'react'
import { ChevronDown, Check } from 'lucide-react'

interface DropdownProps {
  value: string
  options: { value: string; label: string }[]
  onChange: (value: string) => void
  className?: string
}

export default function Dropdown({ value, options, onChange, className = '' }: DropdownProps) {
  const [open, setOpen] = useState(false)
  const ref = useRef<HTMLDivElement>(null)

  useEffect(() => {
    function handleClick(e: MouseEvent) {
      if (ref.current && !ref.current.contains(e.target as Node)) {
        setOpen(false)
      }
    }
    document.addEventListener('mousedown', handleClick)
    return () => document.removeEventListener('mousedown', handleClick)
  }, [])

  const selected = options.find((o) => o.value === value)

  return (
    <div ref={ref} className={`relative ${className}`}>
      <button
        type="button"
        onClick={() => setOpen((o) => !o)}
        className="flex w-full items-center justify-between gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm font-body text-gomobi-dark outline-none transition-colors hover:border-gray-300 focus:border-gomobi-purple"
      >
        <span>{selected?.label ?? 'Select'}</span>
        <ChevronDown size={16} className={`text-gray-400 transition-transform ${open ? 'rotate-180' : ''}`} />
      </button>
      {open && (
        <div className="absolute right-0 top-full z-20 mt-1.5 w-48 rounded-lg border border-gray-100 bg-white py-1.5 shadow-dropdown">
          {options.map((opt) => (
            <button
              key={opt.value}
              type="button"
              onClick={() => {
                onChange(opt.value)
                setOpen(false)
              }}
              className="flex w-full items-center justify-between px-3.5 py-2 text-left text-sm font-body text-gomobi-dark hover:bg-gray-50"
            >
              <span>{opt.label}</span>
              {opt.value === value && <Check size={15} className="text-gomobi-purple" />}
            </button>
          ))}
        </div>
      )}
    </div>
  )
}
