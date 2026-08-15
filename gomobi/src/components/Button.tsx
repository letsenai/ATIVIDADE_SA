import type { ButtonHTMLAttributes, ReactNode } from 'react'

interface ButtonProps extends ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: 'primary' | 'secondary' | 'danger' | 'ghost'
  fullWidth?: boolean
  children: ReactNode
}

const variants = {
  primary:
    'bg-gomobi-purple text-white hover:bg-[#847ade] active:bg-[#7871c9] disabled:opacity-60',
  secondary:
    'bg-white text-gomobi-dark border border-gray-200 hover:bg-gray-50',
  danger: 'bg-red-500 text-white hover:bg-red-600',
  ghost: 'bg-transparent text-gomobi-dark hover:bg-gray-100',
}

export default function Button({
  variant = 'primary',
  fullWidth = false,
  className = '',
  children,
  ...rest
}: ButtonProps) {
  return (
    <button
      className={`${fullWidth ? 'w-full' : ''} rounded-lg px-4 py-2.5 text-sm font-semibold font-body transition-colors duration-150 disabled:cursor-not-allowed ${variants[variant]} ${className}`}
      {...rest}
    >
      {children}
    </button>
  )
}
