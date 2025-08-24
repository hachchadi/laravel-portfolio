declare module 'alpinejs' {
  interface Alpine {
    data(name: string, callback: () => any): void;
    start(): void;
    version?: string;
  }
  
  const Alpine: Alpine;
  export default Alpine;
}

declare global {
  interface Window {
    Alpine: import('alpinejs').default;
    Livewire?: {
      on: (event: string, callback: (data: any) => void) => void;
      hook: (event: string, callback: (data: any) => void) => void;
      find: (id: string) => any;
    };
    portfolioUtils: any;
    portfolioIntegration: any;
    imageOptimization: any;
  }
}

export {};