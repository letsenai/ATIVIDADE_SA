import type { HTMLAttributes, ReactNode } from 'react'

interface CardProps extends HTMLAttributes<HTMLDivElement> {
  children: ReactNode
}

export default function Card({ children, className = '', ...rest }: CardProps) {
  return (
    <div
      className={`rounded-xl border border-gray-100 bg-white p-5 shadow-card ${className}`}
      {...rest}
    >
      {children}
    </div>
  )
}
