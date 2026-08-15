interface LogoProps {
  variant?: 'light' | 'dark'
  size?: 'sm' | 'md' | 'lg'
  showText?: boolean
  lowercase?: boolean
}

const sizes = {
  sm: { icon: 20, text: 'text-sm' },
  md: { icon: 26, text: 'text-base' },
  lg: { icon: 56, text: 'text-2xl' },
}

// Source artwork is 828x641px (~1.29:1). We size by width and let height
// follow the same ratio so the mascot never looks stretched or squashed.
const LOGO_ASPECT_RATIO = 828 / 641

export function LogoMark({ size = 22 }: { size?: number }) {
  return (
    <img
      src="/logo.png"
      alt="GoMobi"
      width={size}
      height={Math.round(size / LOGO_ASPECT_RATIO)}
      style={{ width: size, height: 'auto', aspectRatio: `${LOGO_ASPECT_RATIO}` }}
      className="object-contain select-none"
      draggable={false}
    />
  )
}

export default function Logo({
  variant = 'dark',
  size = 'md',
  showText = true,
  lowercase = false,
}: LogoProps) {
  const { icon, text } = sizes[size]
  const textColor = variant === 'light' ? 'text-white' : 'text-gomobi-dark'
  return (
    <div className="flex items-center gap-2">
      <LogoMark size={icon} />
      {showText && (
        <span className={`font-heading font-semibold ${text} ${textColor}`}>
          {lowercase ? 'gomobi' : 'GoMobi'}
        </span>
      )}
    </div>
  )
}
