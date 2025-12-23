# A11y Essentials 🌐

**A11y Essentials** is a UI for developers who want to build accessible web applications.  
The project aims to support accessibility-aware development by combining automated testing, structured guidance, and progress tracking in one place.

## Key Features

- 🧪 **Automatic Testing**  
  Provides a quick overview of the current implementation and helps identify and fix common accessibility issues.

- 📚 **Knowledge Library**  
  A component-based library offering guidance, explanations, and best practices for implementing accessible web applications.

- ✅ **Checklists**  
  Enables developers to track implementation progress, reflect on completed tasks, and ensure accessibility compliance.

- 📊 **Visual Learning Progress**  
  Displays learning progress and highlights knowledge gaps to support continuous improvement.

---

## Getting Started

The project is containerized and requires **Docker** to run.  
Using Docker ensures a consistent development environment and simplifies the setup process.

### Prerequisites

Make sure the following tools are installed on your system:

- **Docker**
- **Docker Compose** (included with Docker Desktop)

### Installation & Setup

1. Clone the repository:
```bash
   git clone <repository-url>
   cd a11y-essentials
```

2. Build docker 
```bash
   docker compose up -d --build
   cp .env.example .env
```

3. Create .env file
```bash
   cp .env.example .env
```

4. Activate hot module reloading:
```bash
   docker compose exec tooling bash
   npm run dev
```
