import type { InputHTMLAttributes } from 'react'

interface InputProps extends InputHTMLAttributes<HTMLInputElement> {
  label?: string
  error?: string
}

export default function Input({ label, error, id, className = '', ...rest }: InputProps) {
  return (
    <div className="w-full">
      {label && (
        <label htmlFor={id} className="mb-1.5 block text-xs font-semibold text-gomobi-dark">
          {label}
        </label>
      )}
      <input
        id={id}
        className={`w-full rounded-lg border px-3 py-2.5 text-sm font-body text-gomobi-dark placeholder:text-gray-400 outline-none transition-colors focus:border-gomobi-purple focus:ring-1 focus:ring-gomobi-purple ${
          error ? 'border-red-400' : 'border-gray-200'
        } ${className}`}
        {...rest}
      />
      {error && <p className="mt-1 text-xs text-red-500">{error}</p>}
    </div>
  )
}
