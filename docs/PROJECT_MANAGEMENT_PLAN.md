# Project Management Plan - CV Builder

This document operationalizes the provided PM plan into actionable artifacts for this repository.

## Team, Duration, Budget
- Team: 1 Developer
- Duration: 8 weeks
- Budget: Bootstrap (minimal)

## Skills
- Laravel/PHP, Frontend (HTML/CSS/JS), DB Design, Payment Integration

## Methodology
- Agile with weekly Sprints
- Daily self-standup (notes), Weekly review, Kanban board

## Sprints Overview
- Sprint 1: Setup + Auth + DB ⇒ Deliverable: Login/Registration working
- Sprint 2: CV form steps 1–3 ⇒ Deliverable: First steps working
- Sprint 3: CV form steps 4–6 + Template selection + PDF setup ⇒ Deliverable: Full flow
- Sprint 4: 3 templates + PDF output + Payment UI ⇒ Deliverable: MVP test-ready
- Sprint 5: Payment (Fawry/Paymob fallback) + Admin base + Dashboard ⇒ Deliverable: Payments work
- Sprint 6: Admin CRUD + Reports + User mgmt ⇒ Deliverable: Admin complete
- Sprint 7: Testing, perf, responsiveness ⇒ Deliverable: Prod-ready
- Sprint 8: Final QA, SEO, Launch prep ⇒ Deliverable: Live

## Risks & Mitigations
- PDF lib issues ⇒ keep backup lib
- Payment delays ⇒ start early, fallback manual
- Perf issues ⇒ load testing week 6+

## QA & Code Quality
- Unit/Feature tests for critical flows
- Manual testing per sprint
- PSR standards, self-review, docs for complex logic

## Deployment
- Envs: Local, Test VPS, Prod
- Git-based deploy, automated migrations, backups

## KPIs
- Dev: Velocity, Bug rate, Coverage 70%, Page load < 3s
- Biz: Conversion 5%, DAU, ARPU 10 EGP, CSAT > 4/5

## Docs
- API, DB schema, Deploy guide, User guide, FAQ, Admin manual

## Launch
- Soft launch week 8, then public launch week 9 (marketing push)

## Post-Launch
- Weeks 9–12 maintenance, support, analytics, v2 planning

## Success Criteria
- MVP: 100 CVs + 1000 EGP first month, <5% error, positive feedback
- Long-term: Break-even by month 3, 5k users by month 6, 15k EGP MRR, expand scope by month 9
